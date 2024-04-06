<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $lastRate = CropRate::where('crop_type_id', $request->crop_type_id)
        ->where('city_id',$request->city)
        ->whereDate('rate_date', '<',Carbon::now()->format('Y-m-d'))
        ->orderBy('rate_date','desc')->first();
        if($lastRate){
            $min =  $request->min;
            $max =  $request->max;
            $minPercent = ($lastRate->min_price * 0.15);
            $maxPercent = ($lastRate->max_price * 0.15);
            if($min > ($minPercent + $lastRate->min_price) || $min < ($lastRate->min_price - $minPercent)){
                return response()->json(['message' => 'Minimum price should not be greater or less than 15% of last rate'], 422);
            }else if($max > ($maxPercent + $lastRate->max_price) || $max < ($lastRate->max_price - $maxPercent)){
                return response()->json(['message' => 'Maximum price should not be greater or less than 15% of last rate'], 422);
            }
        }
        $rate = CropRate::updateOrCreate([
            'crop_type_id' => $request->crop_type_id,
            'city_id' => $request->city,
            'rate_date' => Carbon::parse($request->rate_date)->format('Y-m-d'),
            // 'rate_date' => Carbon::now()->format('Y-m-d'),
        ],[
            'min_price' => $request->min,
            'max_price' => $request->max,
            'min_price_last' => $lastRate->min_price ?? $request->min,
            'max_price_last' => $lastRate->max_price ?? $request->max,
            'user_id' => $request->user()->id,
        ]);
        \App\Helper\FCM::sendToSetting(6,"نرخ اپڈیٹ","آپ کے شہر کے فصلوں کے ریٹس اپڈیٹ کر دیے گئے ہیں",[
            'type' => 'mand_rate',
            'crop_id' => $request->crop_type_id,
            'city_id' => $request->city,
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
            $r->select(
                'crop_type_id', 'rate_date',
                DB::raw('cast(min(min_price) as float) as min_rate'),
                DB::raw('cast(max(max_price) as float) as max_rate'),
                DB::raw('cast(max(min_price_last) as float) as min_price_last'),
                DB::raw('cast(max(max_price_last) as float) as max_price_last'),
            )->groupBy('rate_date','crop_type_id')
            ->whereIn('rate_date', function($q){
                $q->select(\DB::raw('max(rate_date)'))->from('crop_rates')->groupBy('crop_type_id');
            });
        }])->whereHas('rate')->where('crop_id', $request->crop)->get();
        $people = array("mazeemrehan@gmail.com", "kisanstock@gmail.com", "muhammadashfaqthq786@gmail.com", "jhonhill267@gmail.com");
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
            $typeIds = CropType::where('crop_id',$request->crop_id )->pluck('id');
            $rateLast = CropRate::whereIn('crop_type_id',$typeIds)->orderBy('rate_date','desc')->first();
            $date= $rateLast->rate_date ?? now();
            $rates = Crop::with(['types' => function($t) use ($date){
                $t->with(['rates' => function($r) use ($date){
                    $r->with('city');
                    $r->whereDate('rate_date', $date);
                }]);
            }])->whereId($request->crop_id)->get();
            return response()->json($rates, 200,[]);
           
        }else if($request->type == 'mycities'){
            $isRates = CropRate::whereIn('city_id', json_decode($request->cities))
            ->orderBy('rate_date','desc')->first();
            $date= $isRates->rate_date ?? now();
            $ids = CropRate::whereDate('rate_date', $date)->pluck('crop_type_id');
            $rates = Crop::with(['types' => function($t) use ($ids, $request, $date){
                $t->with(['rates' => function($r) use ($request, $date){
                    $r->whereDate('rate_date', $date);
                    $r->whereIn('city_id', json_decode($request->cities));
                }])->whereIn('id', $ids);
            }])->get();
            return response()->json($rates, 200,[]);
        }

    }

    function trendingCropsGraphs() {
        $ids =  [82,76,60];
        $data = [];
        foreach ($ids as $id) {
            $dates = CropRate::select('rate_date')->orderBy('rate_date','desc')
            ->where('crop_type_id',$id)->limit(20)->distinct()->get();
            $rates =  CropRate::selectRaw('MIN(min_price) as _min, MAX(max_price) as _max, rate_date, crop_type_id')
                ->whereBetween('rate_date', [$dates[$dates->count()-1]['rate_date'],$dates[0]['rate_date']])
                ->where('crop_type_id', $id)
                ->groupBy('rate_date')
                ->groupBy('crop_type_id')
                ->get();  
            $type = CropType::find($id);
            $res['rates'] = $rates;  
            $res['dates'] = [$dates[$dates->count()-1]['rate_date'],$dates[0]['rate_date']];
            $res['crop_name'] = $type->crop->name;
            $res['crop_name_ur'] = $type->crop->name_ur;
            $res['crop_type_name'] = $type->name;
            $res['crop_type_name_ur'] = $type->code;
            $data[] = $res;
        }
        return response()->json($data, 200);
    }
    
}
