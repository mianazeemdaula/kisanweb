<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CropRate extends Model
{
    use HasFactory;
    // protected $appends = ['min_price_last','max_price_last'];
    // protected $with = ['last'];
    protected $casts = [
        'min_price' => 'double',
        'max_price' => 'double',
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
            'rate_date','crop_type_id',
            \DB::raw('cast(avg(min_price) as float) as min_rate'),
            \DB::raw('cast(avg(max_price) as float) as max_rate'),
        )->groupBy('rate_date','crop_type_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
}
