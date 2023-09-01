<?php

namespace App\Exports;

use App\Models\Deal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class DealsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $deals =  Deal::whereHas('seller')->latest()->get();
        $collection = array(['id','name','mobile','whatsApp','crop','type','qty','demand','moisture','status','bids','address','created_at','updated_at']);
        foreach ($deals as  $deal) {
            $data['id'] = $deal->id;
            $data['name'] = $deal->seller->name;
            $data['mobile'] = $deal->seller->mobile;
            $data['whatsapp'] = (string) $deal->seller->whatsapp;
            $data['crop'] = $deal->type->crop->name;
            $data['type'] = $deal->type->name;
            $data['qty'] = $deal->qty . " ". $deal->weight->name ?? '';
            $data['demand'] = $deal->demand;
            $data['moisture'] = $deal->moisture;
            $data['status'] = $deal->status;
            $data['bids'] = $deal->bids->count() ?? 0;
            $data['address'] = $deal->address;
            $data['created_at'] = $deal->created_at;
            $data['updated_at'] = $deal->updated_at;
            $collection[] = $data;
        }
        return new Collection($collection);
    }
}
