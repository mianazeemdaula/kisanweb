<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Crop;
use App\Models\CropRate;
use App\Models\CropType;
use Illuminate\Support\Facades\DB;

class CropCityRateController extends Controller
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
        // $paginate =  CropRate::cityRate()->with(['city'])->where('crop_type_id', $id)
        // ->leftJoin('cities', function($join) { 
        //     $join->on('cities.id', '=', 'crop_rates.city_id');
        // })
        // ->orderBy('cities.name','asc')
        // ->orderBy('rate_date','desc')
        // ->paginate();
        // $data = collect($paginate->items());
        // $data->each->append('min_city_price_last','max_city_price_last');
        // $paginate->setCollection($data);
        // return response()->json($paginate, 200, []);

        // $rates = DB::table('crop_rates as cr')
        //     ->select(
        //         'cr.city_id',
        //         'cr.crop_type_id',
        //         DB::raw('AVG(cr.min_price) AS min_rate'),
        //         DB::raw('AVG(cr.max_price) AS max_rate'),
        //         'cr.rate_date',
        //         DB::raw('AVG(prev_cr.min_price) AS min_city_price_last'),
        //         DB::raw('AVG(prev_cr.max_price) AS max_city_price_last'),
        //     )
        //     ->leftJoin('crop_rates as prev_cr', function($join) {
        //         $join->on('cr.crop_type_id', '=', 'prev_cr.crop_type_id');
        //         $join->on('prev_cr.rate_date', '=', DB::raw('(
        //             SELECT MAX(rate_date) FROM crop_rates
        //             WHERE crop_type_id = cr.crop_type_id AND rate_date < cr.rate_date
        //         )'));
        //     })
        //     ->join('cities', 'cities.id', '=', 'cr.city_id')
        //     // ->whereIn('cr.city_id', $id)
        //     ->where('cr.crop_type_id',$id)
        //     ->groupBy('cr.city_id', 'cr.crop_type_id', 'cr.rate_date')
        //     ->orderBy('cr.rate_date', 'desc')
        //     ->paginate();

            $rates  = CropRate::cityWiseRate()
            ->with(['city'])
            ->where('cr.crop_type_id',$id)
            ->orderBy('cities.name','asc')
            ->paginate();

            return response()->json($rates, 200,[]);
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

    public function cityHistory(Request $request)
    {
        $paginate =  CropRate::cityRateHistory()
        ->where('cr.crop_type_id', $request->crop)
        ->where('cr.city_id', $request->city)
        ->paginate();
        // $data = collect($paginate->items());
        // $data->each->append('min_price_last','max_price_last');
        // $paginate->setCollection($data);
        return response()->json($paginate, 200, []);
    }
    
}
