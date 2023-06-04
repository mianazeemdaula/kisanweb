<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CropType extends Model
{
    use HasFactory;

    protected $casts = [
        'crop_id' => 'integer',
    ];

    /**
     * Get the user that owns the Offer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function crop()
    {
        return $this->belongsTo(Crop::class, 'crop_id');
    }

    
    public function commissionShopRates(): HasMany
    {
        return $this->hasMany(CommissionShopRate::class, 'crop_type_id');
    }

    public function commissionShopRate(): HasOne
    {
        return $this->hasOne(CommissionShopRate::class, 'crop_type_id')->orderBy('rate_date', 'desc');
    }

    public function rates(): HasMany
    {
        return $this->hasMany(CropRate::class)->orderBy('rate_date','desc');
    }
    public function rate(): HasOne
    {
        return $this->hasOne(CropRate::class)->orderBy('rate_date','desc');
    }
}
