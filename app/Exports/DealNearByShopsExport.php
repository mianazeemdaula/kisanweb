<?php

namespace App\Exports;

use App\Models\Deal;
use App\Models\CommissionShop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class DealNearByShopsExport implements FromCollection
{

    /**
    * @return \Illuminate\Support\Collection
    */

    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        $deal =  Deal::find($this->id);
        $shops =  CommissionShop::query()->orderByDistance('location',$deal->location)
        ->withDistance('location', $deal->location)->whereDistance('location',$deal->location, '<', 20)
        ->whereActive(true)->with(['city', 'user'])->get();
        $collection = array(['id','name','user_name','mobile','whatsapp','shop_mobile','shop_whatsapp','distance']);
        foreach ($shops as  $shop) {
            $data['id'] = $shop->id;
            $data['name'] = $shop->name;
            $data['user_name'] = $shop->user->name;
            $data['mobile'] = $shop->user->mobile;
            $data['whatsapp'] = $shop->user->whatsapp;
            $data['shop_mobile'] = $shop->social_links['mobile'];
            $data['shop_whatsapp'] = $shop->social_links['whatsapp'];
            $data['distance'] = $shop->distance;
            $collection[] = $data;
        }
        return new Collection($collection);
    }
}
