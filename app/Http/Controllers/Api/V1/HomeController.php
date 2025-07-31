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
                $q->whereHas('seller', function($sellerQuery) use($reqeust) {
                    $sellerQuery->where('name', 'like', '%' . $reqeust->text . '%');
                });
            });
            // where biders name
            $query->orWhereHas('bids', function($bidQuery) use($reqeust) {
                $bidQuery->whereHas('buyer', function($buyerQuery) use($reqeust) {
                    $buyerQuery->where('name', 'like', '%' . $reqeust->text . '%');
                });  
            });

            // where type
            $query->orWhereHas('type', function($typeQuery) use($reqeust) {
                $typeQuery->where('name', 'like', '%' . $reqeust->text . '%');
                // or where type urdu name
                $typeQuery->orWhere('name_ur', 'like', '%' . $reqeust->text . '%');
                // where crop name
                $typeQuery->orWhereHas('crop', function($cropQuery) use($reqeust) {
                    $cropQuery->where('name', 'like', '%' . $reqeust->text . '%')
                    ->orWhere('name_ur', 'like', '%' . $reqeust->text . '%');
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
        // $query->whereHas('subcategory', function($query) use($catid) {
        //     $query->whereHas('category', function($query2) use($catid) {
        //         $query2->where('id', $catid);
        //     });
        // });
        $ids = [];
        if($reqeust->subcat){
            $ids = [$reqeust->subcat];
        }else if($reqeust->cat){
            $ids = Category::where('parent_id', $reqeust->cat)->pluck('id');
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
                $q->whereHas('user', function($userQuery) use($reqeust) {
                    $userQuery->where('name', 'like', '%' . $reqeust->text . '%');
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

    public function userDeals()
    {
        $user = auth()->user();
        $data = Deal::with(['bids' => function($q) use($user) {
            $q->with(['buyer'])->where('buyer_id', $user->id);
        }, 'seller', 'packing', 'weight' , 'media'])->where('seller_id', $user->id)->paginate();
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
