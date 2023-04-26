<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;


class CropRate extends Model
{
    use HasFactory;
    // protected $appends = ['min_price_last','max_price_last'];
    // protected $with = ['last'];
    protected $casts = [
        'min_price' => 'double',
        'max_price' => 'double',
        'city_id' => 'integer',
        // 'crop_type_id' => 'integer'
    ];

    protected $fillable = [
        'city_id',
        'crop_type_id',
        'min_price',
        'max_price',
        'rate_date',
        'user_id'
    ];

    // Scops

    public function scopeRate($query){
        return $query->select(
            'crop_type_id', 'rate_date',
            \DB::raw('cast(min(min_price) as float) as min_rate'),
            \DB::raw('cast(max(max_price) as float) as max_rate'),
        )->groupBy('rate_date','crop_type_id')
        ->whereIn('rate_date', function($q){
            $q->select(\DB::raw('max(rate_date)'))->from('crop_rates')->groupBy('crop_type_id');
        });
    }

    public function scopeCityHistory($query){
        return $query->select(
            'rate_date','crop_type_id','city_id',
            \DB::raw('cast(min(min_price) as float) as min_rate'),
            \DB::raw('cast(max(max_price) as float) as max_rate'),
        )->groupBy('rate_date','crop_type_id','city_id');
    }

    public function scopeCityRate($query){
        return $query->select(
            'crop_type_id', 'city_id',
            \DB::raw('cast(min(min_price) as float) as min_rate'),
            \DB::raw('cast(max(max_price) as float) as max_rate'),
            \DB::raw('max(rate_date) as rate_date'),
        )->groupBy('crop_type_id', 'city_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function cropType(): BelongsTo
    {
        return $this->belongsTo(CropType::class);
    }

    public function getMinPriceLastAttribute()
    {
        return (double) $this->whereDate('rate_date','<=',\Carbon\Carbon::parse($this->rate_date)->subDays(1))
        ->where('crop_type_id', $this->crop_type_id)->groupBy('rate_date')
        ->orderBy('rate_date','desc')->avg('min_price') ?? $this->min_price;
    }

    public function getMaxPriceLastAttribute()
    {
        return (double) $this->whereDate('rate_date','<=',\Carbon\Carbon::parse($this->rate_date)->subDays(1))
        ->where('crop_type_id', $this->crop_type_id)->groupBy('rate_date')
        ->orderBy('rate_date','desc')->avg('max_price') ?? $this->max_price;
    }

    public function getMinCityPriceLastAttribute()
    {
        return (double) $this->whereDate('rate_date','<=',\Carbon\Carbon::parse($this->rate_date)->subDays(1))
        ->where('crop_type_id', $this->crop_type_id)
        ->where('city_id', $this->city_id)->groupBy('rate_date')
        ->orderBy('rate_date','desc')->min('min_price') ?? $this->min_price;
    }

    public function getMaxCityPriceLastAttribute()
    {
        return (double) $this->whereDate('rate_date','<=',\Carbon\Carbon::parse($this->rate_date)->subDays(1))
        ->where('crop_type_id', $this->crop_type_id)
        ->where('city_id', $this->city_id)->groupBy('rate_date')
        ->orderBy('rate_date','desc')->max('max_price') ?? $this->max_price;
    }

    // 

    public static function getCropRatesByTypeIds($typeIds)
    {
        $query = DB::table('crop_rates as cr')
            ->select(
                'cr.crop_type_id',
                DB::raw('AVG(cr.min_price) AS avg_min_price'),
                DB::raw('AVG(cr.max_price) AS avg_max_price'),
                DB::raw('AVG(cr.max_price) AS avg_max_price'),
                DB::raw('MAX(cr.rate_date) AS rate_date'),
                DB::raw('AVG(prev_cr.min_price) AS prev_avg_min_price'),
                DB::raw('AVG(prev_cr.max_price) AS prev_avg_max_price')
            )
            ->leftJoin('crop_rates as prev_cr', function($join) {
                $join->on('cr.crop_type_id', '=', 'prev_cr.crop_type_id')
                     ->on(DB::raw('prev_cr.rate_date'), '=', DB::raw('(
                        SELECT MAX(rate_date) FROM crop_rates WHERE crop_type_id = cr.crop_type_id AND rate_date < cr.rate_date
                     )'));
            })
            ->join(DB::raw('(
                SELECT crop_type_id, MAX(rate_date) AS max_rate_date
                FROM crop_rates
                GROUP BY crop_type_id
            ) as sub'), function($join) use($typeIds) {
                $join->on('cr.crop_type_id', '=', 'sub.crop_type_id')
                     ->on('cr.rate_date', '=', 'sub.max_rate_date')
                     ->on('cr.crop_type_id', 'in', $typeIds);
            })
            ->whereIn('cr.crop_type_id', $typeIds)
            ->groupBy('cr.crop_type_id', 'cr.rate_date');
        
        return $query->get();
    }

    public function scopeCityWiseRate($query)
    {
        return $query->from('crop_rates as cr')->select(
            'cr.city_id',
            'cr.crop_type_id',
            DB::raw('MIN(cr.min_price) AS min_rate'),
            DB::raw('MAX(cr.max_price) AS max_rate'),
            DB::raw('MAX(cr.rate_date) AS rate_date'),
            DB::raw('MIN(prev_cr.min_price) AS min_city_price_last'),
            DB::raw('MAX(prev_cr.max_price) AS max_city_price_last'),
        )
        ->leftJoin('crop_rates as prev_cr', function($join) {
            $join->on('cr.crop_type_id', '=', 'prev_cr.crop_type_id');
            $join->on('prev_cr.rate_date', '=', DB::raw('(
                SELECT MAX(rate_date) FROM crop_rates
                WHERE crop_type_id = cr.crop_type_id AND rate_date < cr.rate_date AND city_id = cr.city_id
            )'));
        })->whereIn('cr.id', function($query) {
            $query->select(DB::raw('MAX(id)'))
                ->from('crop_rates')
                ->groupBy('city_id','crop_type_id');
        })
        ->join('cities', 'cities.id', '=', 'cr.city_id')
        ->groupBy('cr.city_id', 'cr.crop_type_id', 'cr.rate_date')
        ->orderBy('cr.rate_date', 'desc');
    }

    public function scopeCityRateHistory($query)
    {
        return $query->from('crop_rates as cr')->select(
            'cr.city_id',
            'cr.crop_type_id',
            DB::raw('MIN(cr.min_price) AS min_rate'),
            DB::raw('MAX(cr.max_price) AS max_rate'),
            DB::raw('MAX(cr.rate_date) AS rate_date'),
            DB::raw('MIN(prev_cr.min_price) AS min_price_last'),
            DB::raw('MAX(prev_cr.max_price) AS max_price_last'),
        )
        ->leftJoin('crop_rates as prev_cr', function($join) {
            $join->on('cr.crop_type_id', '=', 'prev_cr.crop_type_id');
            $join->on('prev_cr.rate_date', '=', DB::raw('(
                SELECT MAX(rate_date) FROM crop_rates FORCE INDEX (idx_crop_type_id_rate_date_city_id) 
                WHERE crop_type_id = cr.crop_type_id AND rate_date < cr.rate_date AND city_id = cr.city_id
            )'));
        })
        ->groupBy('cr.city_id', 'cr.crop_type_id', 'cr.rate_date')
        ->orderBy('cr.rate_date', 'desc');
    }
}
