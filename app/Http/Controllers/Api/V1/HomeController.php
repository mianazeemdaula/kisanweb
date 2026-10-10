<?php

namespace App\Http\Controllers\Api\V1;

use App\Support\ApiCache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use MatanYadaev\EloquentSpatial\Objects\Point;
// Models
use App\Models\Crop;
use App\Models\Deal;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoryDeal;

class HomeController extends Controller
{
    public function crops()
    {
        return ApiCache::json('crops', 'with_types', 86400, function () {
            return Crop::with('types')->has('types')->where('active', true)
            ->orderBy('sort')->get();
        });
    }

    public function popular(Request $reqeust)
    {
        // Free-text searches are one-offs; everything else is the same list
        // for every user, so it is served from the cache.
        if(!$reqeust->text){
            $key = 'popular:'.ApiCache::key($reqeust, ['crop', 'lat', 'lng', 'sortype', 'page']);
            return ApiCache::json('deals', $key, 60, fn () => $this->popularDeals($reqeust));
        }
        return response()->json($this->popularDeals($reqeust), 200);
    }

    private function popularDeals(Request $reqeust)
    {
        $query = Deal::query();
        if($reqeust->crop){
            $query->whereHas('type', function($query) use($reqeust) {
                $query->where('crop_id', $reqeust->crop);
            });
        }
        if($reqeust->lat && $reqeust->lng){
            $point = new Point($reqeust->lat, $reqeust->lng, 4326);
            $query->whereDistance('location', $point , '<', 10);
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
                                ->orWhere('code', 'like', '%' . $reqeust->text . '%')
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
        }, 'seller', 'packing', 'weight', 'media', 'type.crop', 'reactions'])
        ->whereHas('seller')
        ->whereNotIn('status',['accepted','expired'])
        ->paginate();
        return $data;
    }

    public function catdeals(Request $reqeust)
    {
        if(!$reqeust->text){
            $key = 'list:'.ApiCache::key($reqeust, ['subcat', 'category_id', 'sortype', 'page']);
            return ApiCache::json('cat_deals', $key, 60, fn () => $this->categoryDeals($reqeust));
        }
        return response()->json($this->categoryDeals($reqeust), 200);
    }

    private function categoryDeals(Request $reqeust)
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
        }, 'user', 'media', 'subcategory.category', 'packing', 'weight', 'reactions'])
        ->whereHas('user')
        ->whereNotIn('status',['accepted','expired'])
        ->paginate();
        return $data;
    }

    /**
     * The user's deal history.
     *   type=bids : deals (crop and category) the user has bid on
     *   type=all  : deals (crop and category) the user is selling
     *   otherwise : crop deals the user is selling (older app builds, which
     *               load their category deals from user-cat-deals)
     * With user_id the same lists are returned for that user (profile screen).
     * Every deal carries all of its bids, newest deals first.
     */
    public function userDeals(Request $request)
    {
        // user_id lets a profile screen list another user's deals; without
        // it the list is the signed-in user's own.
        $user = $request->filled('user_id')
            ? User::findOrFail($request->user_id)
            : auth()->user();

        if ($request->type === 'bids') {
            $crop = $this->cropDealsWithBids()
                ->whereHas('bids', fn($q) => $q->where('buyer_id', $user->id))->get();
            $category = $this->categoryDealsWithBids()
                ->whereHas('bids', fn($q) => $q->where('buyer_id', $user->id))->get();
            return response()->json($this->mergedDealsPage($request, $crop, $category), 200);
        }

        if ($request->type === 'all') {
            $crop = $this->cropDealsWithBids()->where('seller_id', $user->id)->get();
            $category = $this->categoryDealsWithBids()->where('user_id', $user->id)->get();
            return response()->json($this->mergedDealsPage($request, $crop, $category), 200);
        }

        $data = $this->cropDealsWithBids()->where('seller_id', $user->id)
            ->orderBy('id', 'desc')->paginate();

        return response()->json($data, 200);
    }

    public function userCatDeals()
    {
        $data = $this->categoryDealsWithBids()->where('user_id', auth()->id())
            ->orderBy('id', 'desc')->paginate();
        return response()->json($data, 200);
    }

    private function cropDealsWithBids()
    {
        return Deal::with(['bids' => function($q) {
            $q->with(['buyer'])->whereHas('buyer');
        }, 'seller', 'packing', 'weight', 'media', 'type.crop', 'reactions', 'reviews']);
    }

    private function categoryDealsWithBids()
    {
        return CategoryDeal::with(['bids' => function($q) {
            $q->with(['buyer'])->whereHas('buyer');
        }, 'user', 'packing', 'weight', 'media', 'subcategory.category', 'reactions']);
    }

    /** Crop and category deals as one list, newest first, 15 per page. */
    private function mergedDealsPage(Request $request, $cropDeals, $categoryDeals)
    {
        $cropDeals->each(fn($deal) => $deal->deal_type = 'crop');
        $categoryDeals->each(fn($deal) => $deal->deal_type = 'category');
        $merged = $cropDeals->concat($categoryDeals)->sortByDesc('created_at')->values();

        $perPage = 15;
        $page = max(1, (int) $request->get('page', 1));

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $merged->forPage($page, $perPage)->values(), $merged->count(), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    public function subcats($id)
    {
        return ApiCache::json('categories', 'subcats:'.(int) $id, 86400, function () use ($id) {
            return Category::with(['subcategories'])->where('parent_id', $id)->get();
        });
    }

    public function wamessage(Request $request)
    {   Log::debug($request->all());
        return $request->hub_challenge ?? 'Thank you for testing';
    }


}
