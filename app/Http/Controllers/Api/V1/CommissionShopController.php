<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Image;

use App\Models\CommissionShop;
use App\Models\CommissionShopRate;

class CommissionShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['shop'] = CommissionShop::with(['crops', 'city', 'user'])
        ->where('user_id', auth()->id())->first();
       if($data['shop']){
            $data['rates'] =  \App\Models\Crop::with(['types' => function($q) use ($data){
                $q->with(['commissionShopRate' => function($rate) use($data){
                    $rate->where('commission_shop_id', $data['shop']->id);
                }])->whereIn('id',CommissionShopRate::select('crop_type_id')->where('commission_shop_id', $data['shop']->id)->distinct()->get()->toArray());
            }])->get();
       }
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'address' => 'required',
            'logo' => 'required',
            'banner' => 'required',
            'about' => 'required',
            'city' => 'required',
            'shop_number' => 'required',
        ]);
        $shop = new CommissionShop();
        $shop->user_id = $request->user()->id;
        $shop->name = $request->name;
        $shop->about = $request->about;
        $shop->city_id = $request->city;
        $shop->shop_number = $request->shop_number;
        if($request->has('logo')){
            $file =  $request->file('logo');
            $ext = $file->getClientOriginalExtension();
            $fileName = Str::random(15).'.'.$ext;
            $path = "shops/".$fileName;
            $image = Image::make($file->getRealPath());
            $image->save($path);
            $shop->logo = $path;
        }
        if($request->has('banner')){
            $file =  $request->file('banner');
            $ext = $file->getClientOriginalExtension();
            $fileName = Str::random(15).'.'.$ext;
            $path = "shops/".$fileName;
            $image = Image::make($file->getRealPath());
            $image->save($path);
            $shop->banner = $path;
        }
        $shop->address = $request->address;
        $shop->location = new Point($request->lat, $request->lng);
        $socials = [];
        if($request->facebook){
            $socials['facebook'] = $request->facebook;
        }if($request->email){
            $socials['email'] = $request->email;
        }if($request->web){
            $socials['web'] = $request->web;
        }if($request->whatsapp){
            $socials['whatsapp'] = $request->whatsapp;
        }if($request->mobile){
            $socials['mobile'] = $request->mobile;
        }
        $shop->social_links = $socials;
        $shop->save();
        return response()->json($shop, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['shop'] = CommissionShop::with(['crops', 'city', 'user'])->findOrFail($id);
        $data['rates'] =  \App\Models\Crop::with(['types' => function($q) use ($data){
            $q->with(['commissionShopRate' => function($rate) use($data){
                $rate->where('commission_shop_id', $data['shop']->id);
            }]);
        }])->get();
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shop = CommissionShop::with('crops')->findOrFail($id);
        return response()->json($data, 200, $headers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'address' => 'required',
            'about' => 'required',
        ]);
        $shop = CommissionShop::findOrFail($id);
        $shop->name = $request->name;
        $shop->about = $request->about;
        $shop->shop_number = $request->shop_number;
        if($request->has('logo')){
            $file =  $request->file('logo');
            $ext = $file->getClientOriginalExtension();
            $fileName = Str::random(15).'.'.$ext;
            $path = "shops/".$fileName;
            $image = Image::make($file->getRealPath());
            $image->save($path);
            $shop->logo = $path;
        }
        if($request->has('banner')){
            $file =  $request->file('banner');
            $ext = $file->getClientOriginalExtension();
            $fileName = Str::random(15).'.'.$ext;
            $path = "shops/".$fileName;
            $image = Image::make($file->getRealPath());
            $image->save($path);
            $shop->banner = $path;
        }
        $shop->address = $request->address;
        $shop->location = new Point($request->lat, $request->lng);
        $socials = [];
        if($request->facebook){
            $socials['facebook'] = $request->facebook;
        }if($request->email){
            $socials['email'] = $request->email;
        }if($request->web){
            $socials['web'] = $request->web;
        }if($request->whatsapp){
            $socials['whatsapp'] = $request->whatsapp;
        }if($request->mobile){
            $socials['mobile'] = $request->mobile;
        }
        $shop->social_links = $socials;
        $shop->save();
        return response()->json($shop, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cropsUpdate(Request $request)
    {
        $request->validate([
            'crops' => 'required',
        ]);
        $request->user()->commissionShop->crops()->sync(json_decode($request->crops));
        return $this->index();
    }

    public function getShopCropsWithType()
    {
        $data = auth()->user()->commissionShop->crops()->with(['types'])->get();
        return response()->json($data, 200);
    }

    public function postCropRate(Request $request)
    {
        $request->validate([
            'max_price' => 'required',
            'min_price' => 'required',
            'crop_type_id' => 'required',
        ]);
        $data =  CommissionShopRate::updateOrCreate([
            'commission_shop_id' => $request->user()->commissionShop->id,
            'crop_type_id' => $request->crop_type_id,
            'rate_date' => date('Y-m-d'),
        ],[
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
        ]);
        return response()->json($data, 200);
    }

    public function getNearByShop(Request $request)
    {
        $user = $request->user();
        $address = $user->addresses()->whereDefault(true)->first();
        $shops = CommissionShop::query()->orderByDistance('location',$address->location)
        ->with(['city', 'user'])
        ->paginate();
        return response()->json($shops, 200);
    }
}
