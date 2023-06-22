<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Crop;
use App\Models\CropRate;
use App\Models\CropType;

class CropRateController extends Controller
{
    public function index()
    {
        $data = Crop::with(['types' => function($q){
            $q->with(['rate' => function($r){
                $r->select(
                    'rate_date','crop_type_id',
                    \DB::raw('cast(min(min_price) as float) as min_rate'),
                    \DB::raw('cast(max(max_price) as float) as max_rate'),
                )->groupBy('rate_date','crop_type_id')
                ->whereDate('rate_date','2023-02-02');
            }])->whereHas('rate');
        }])->get();
        return response()->json($data, 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'crop_type_id' => 'required',
            'min' => 'required',
            'max' => 'required',
            'city' => 'required',
            'rate_date' => 'required',
        ]);
        $rate = CropRate::updateOrCreate([
            'crop_type_id' => $request->crop_type_id,
            'city_id' => $request->city,
            'user_id' => $request->user()->id,
            'rate_date' => Carbon::parse($request->rate_date)->format('Y-m-d'),
        ],[
            'min_price' => $request->min,
            'max_price' => $request->max,
        ]);
        return response()->json($rate, 200);
    }

    public function show($id)
    {
        $paginate =  CropRate::rate()->where('crop_type_id', $id)
        ->orderBy('rate_date','desc')
        ->paginate();
        $data = collect($paginate->items());
        $data->each->append('min_price_last','max_price_last');
        $paginate->setCollection($data);
        return response()->json($paginate, 200, []);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

  
    public function destroy($id)
    {
        //
    }

    public function filter(Request $request)
    {
        $data['rates'] = CropType::with(['rate' => function($r) use($request) {
            $r->rate();
        }])->whereHas('rate')->where('crop_id', $request->crop)->get();
        $data['rates']->each(function($item) {
            $d  = CropRate::select(
                \DB::raw('max(max_price) as max_last'),
                \DB::raw('min(min_price) as min_last'),
            )
            ->whereNotIn('rate_date', [$item->rate_date])
            // ->whereDate('rate_date' ,'<',$item->rate_date)
            ->groupBy('rate_date')
            ->orderBy('rate_date', 'desc')
            ->where('crop_type_id', $item->crop_type_id)->first();
            $item->rate->min_price_last = $d == null ? 0 : $d->min_last; 
            $item->rate->max_price_last = $d == null ? 0 : $d->max_last; 
         });
        $people = array("mazeemrehan@gmail.com", "kisanstock@gmail.com", "muhammadashfaqthq786@gmail.com");
        $data['mandi_user'] = (bool) in_array($request->user()->email, $people);
        return response()->json($data, 200);
    }

    public function getRates(Request $request)
    {
        if($request->type == 'crops'){
            $data =  CropType::with(['rate' => function($r) use($request) {
                $r->rate();
            }])->whereHas('rate')->where('crop_id', $request->crop)->get();
            return response()->json($data, 200,[]);
        }else if($request->type == 'today'){
            $isRates = CropRate::where('crop_type_id', $request->crop_type)->latest()->first();
            if($isRates){
                 // $ids = CropRate::whereDate('rate_date',$date)->pluck('crop_type_id');
                $rates = Crop::with(['types' => function($t) use ($isRates){
                    $t->with(['rates' => function($r,) use($isRates){
                        $r->with('city');
                        $r->whereDate('rate_date', $isRates->rate_date);
                    }])->where('id',$isRates->crop_type_id);
                }])->get();
                return response()->json($rates, 200,[]);
            }
            return response()->json([], 200,[]);
           
        }else if($request->type == 'mycities'){
            $isRates = CropRate::whereDate('rate_date',now())->first();
            $date= now();
            if(!$isRates){
                $date= now()->subDays(1);
            }
            $ids = CropRate::whereDate('rate_date', $date)->pluck('crop_type_id');
            $rates = Crop::with(['types' => function($t) use ($ids, $request, $date){
                $t->with(['rates' => function($r) use ($request, $date){
                    $r->with('city');
                    $r->whereDate('rate_date', $date);
                    $r->whereIn('city_id', json_decode($request->cities));
                }])->whereIn('id', $ids);
            }])->get();
            return response()->json($rates, 200,[]);
        }

    }
    
}
