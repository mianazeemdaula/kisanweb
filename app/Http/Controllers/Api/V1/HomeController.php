<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use MatanYadaev\EloquentSpatial\Objects\Point;
// Models
use App\Models\Crop;
use App\Models\Deal;
use App\Models\Category;
use App\Models\CategoryDeal;

class HomeController extends Controller
{
    public function crops()
    {
        $data = Crop::with('types')->has('types')->where('active', true)
        ->orderBy('sort')->get();
        return response()->json($data, 200);
    }

    public function popular(Request $reqeust)
    {
        $query = Deal::query();
        if($reqeust->crop){
            $query->whereHas('type', function($query) use($reqeust) {
                $query->where('crop_id', $reqeust->crop);
            });
        }
        if($reqeust->lat && $reqeust->lng){
            $point = new Point($reqeust->lat, $reqeust->lng, 4326);
            $query->whereDistance('location', $point , '<', 10)->count();
        }

        if($reqeust->text){
            $query->where(function($q) use($reqeust) {
                $q->where('note', 'like', '%' . $reqeust->text . '%')
                  ->orWhere('address', 'like', '%' . $reqeust->text . '%')
                  ->orWhereHas('seller', function($sellerQuery) use($reqeust) {
                      $sellerQuery->where('name', 'like', '%' . $reqeust->text . '%');
                  })
                  ->orWhereHas('bids', function($bidQuery) use($reqeust) {
                      $bidQuery->whereHas('buyer', function($buyerQuery) use($reqeust) {
                          $buyerQuery->where('name', 'like', '%' . $reqeust->text . '%');
                      });  
                  })
                  ->orWhereHas('type', function($typeQuery) use($reqeust) {
                      $typeQuery->where('name', 'like', '%' . $reqeust->text . '%')
                                ->orWhere('name_ur', 'like', '%' . $reqeust->text . '%')
                                ->orWhereHas('crop', function($cropQuery) use($reqeust) {
                                    $cropQuery->where('name', 'like', '%' . $reqeust->text . '%')
                                              ->orWhere('name_ur', 'like', '%' . $reqeust->text . '%');
                                });
                  });
            });
        }

        if($reqeust->sortype){
            // $query->orderBy();
        }else{
            $query->orderBy('id', 'desc');
        }
        $data = $query->with(['bids' => function($q){
            $q->with(['buyer'])->whereHas('buyer');
        }, 'seller', 'packing', 'weight', 'media', 'type.crop'])
        ->whereHas('seller')
        ->whereNotIn('status',['accepted','expired'])
        ->paginate();
        return response()->json($data, 200);
    }

    public function catdeals(Request $reqeust)
    {
        $query = CategoryDeal::query();

        $ids = [];
        if($reqeust->subcat){
            $ids = [$reqeust->subcat];
        }else if($reqeust->category_id){
            $ids = Category::where('parent_id', $reqeust->category_id)->pluck('id');
        }
        $query->whereHas('subcategory', function($query) use($ids) {
            $query->whereIn('category_id', $ids);
        });
        // if($reqeust->lat && $reqeust->lng){
        //     $point = new Point($reqeust->lat, $reqeust->lng, 4326);
        //     $query->whereDistance('location', $point , '<', 10)->count();
        // }
        if($reqeust->text){
            $query->where(function($q) use($reqeust) {
                $q->where('note', 'like', '%' . $reqeust->text . '%')
                  ->orWhere('address', 'like', '%' . $reqeust->text . '%')
                  ->orWhereHas('user', function($userQuery) use($reqeust) {
                      $userQuery->where('name', 'like', '%' . $reqeust->text . '%');
                  })
                  ->orWhereHas('bids', function($bidQuery) use($reqeust) {
                      $bidQuery->whereHas('buyer', function($buyerQuery) use($reqeust) {
                          $buyerQuery->where('name', 'like', '%' . $reqeust->text . '%');
                      });  
                  })
                  ->orWhereHas('subcategory', function($subQuery) use($reqeust) {
                      $subQuery->where('name', 'like', '%' . $reqeust->text . '%')
                               ->orWhere('name_ur', 'like', '%' . $reqeust->text . '%')
                               ->orWhereHas('category', function($catQuery) use($reqeust) {
                                   $catQuery->where('name', 'like', '%' . $reqeust->text . '%')
                                            ->orWhere('name_ur', 'like', '%' . $reqeust->text . '%');
                               });
                  });
            });
        }
        if($reqeust->sortype){
            // $query->orderBy();
        }else{
            $query->orderBy('id', 'desc');
        }
        $data = $query->with(['bids' => function($q){
            $q->with(['buyer'])->whereHas('buyer');
        }, 'user', 'media', 'subcategory.category', 'packing', 'weight'])
        ->whereHas('user')
        ->whereNotIn('status',['accepted','expired'])
        ->paginate();
        return response()->json($data, 200);
    }

    public function userDeals(Request $request)
    {
        $user = auth()->user();

        if ($request->type === 'bids') {
            // Get crop deals where user has placed bids
            $cropDeals = Deal::with(['bids' => function($q) use($user) {
                $q->with(['buyer'])->where('buyer_id', $user->id);
            }, 'seller', 'packing', 'weight', 'media', 'type.crop'])
            ->whereHas('bids', fn($q) => $q->where('buyer_id', $user->id))
            ->get()
            ->map(function($deal) {
                $deal->deal_type = 'crop';
                return $deal;
            });

            // Get category deals where user has placed bids
            $catDeals = CategoryDeal::with(['bids' => function($q) use($user) {
                $q->with(['buyer'])->where('buyer_id', $user->id);
            }, 'user', 'packing', 'weight', 'media', 'subcategory.category'])
            ->whereHas('bids', fn($q) => $q->where('buyer_id', $user->id))
            ->get()
            ->map(function($deal) {
                $deal->deal_type = 'category';
                return $deal;
            });

            // Merge and sort by latest first
            $merged = $cropDeals->concat($catDeals)->sortByDesc('created_at')->values();

            // Manual pagination
            $perPage = 15;
            $page = $request->get('page', 1);
            $total = $merged->count();
            $items = $merged->forPage($page, $perPage)->values();

            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $items, $total, $perPage, $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return response()->json($data, 200);
        }

        // Default: seller's own deals
        $data = Deal::with(['bids' => function($q) use($user) {
            $q->with(['buyer'])->where('buyer_id', $user->id);
        }, 'seller', 'packing', 'weight', 'media', 'type.crop'])->where('seller_id', $user->id)->paginate();

        return response()->json($data, 200);
    }

    public function userCatDeals()
    {
        $user = auth()->user();
        $data = CategoryDeal::with(['bids' => function($q) use($user) {
            $q->with(['buyer'])->where('buyer_id', $user->id);
        }, 'user', 'packing', 'weight' , 'media'])->where('user_id', $user->id)->paginate();
        return response()->json($data, 200);
    }

    public function subcats($id)
    {
        $data = Category::with(['subcategories'])->where('parent_id', $id)->get();
        return response()->json($data, 200);
    }

    public function wamessage(Request $request)
    {   Log::debug($request->all());
        return $request->hub_challenge ?? 'Thank you for testing';
    }


}
