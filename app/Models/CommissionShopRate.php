<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionShopRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'max_price', 'min_price','crop_type_id', 'commission_shop_id', 'rate_date'
    ];

    protected $casts = [
        'crop_type_id' => 'integer',
        'commission_shop_id' => 'integer',
        'min_price' => 'float',
        'max_price' => 'float',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(CommissionShop::class, 'commission_shop_id');
    }

    public function cropType()
    {
        return $this->belongsTo(CropType::class, 'crop_type_id');
    }

    public function scopeGetLatestRates($query, $commission_shop_id)
    {
        return $query->select('commission_shop_rates.min_price as min_rate', 'commission_shop_rates.max_price as max_rate', 'commission_shop_rates.crop_type_id')
            ->where('commission_shop_id', $commission_shop_id)
            ->where('rate_date', function ($subquery) use ($commission_shop_id) {
                $subquery->selectRaw('MAX(rate_date)')
                    ->from('commission_shop_rates')
                    ->whereColumn('commission_shop_id', 'commission_shop_rates.commission_shop_id')
                    ->whereColumn('crop_type_id', 'commission_shop_rates.crop_type_id');
            });
    }
}
