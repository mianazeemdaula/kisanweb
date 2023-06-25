<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use Illuminate\Support\Str;

class CommissionShop extends Model
{
    use HasFactory;
    

    protected $casts = [
        'user_id' => 'integer',
        'social_links' => 'json',
        'location' => Point::class,
    ];

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }


    public function getLogoAttribute($value){
        return url($value);
    }

    public function getBannerAttribute($value){
        return url($value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function crops()
    {
        return $this->belongsToMany(Crop::class, 'commission_shop_crop');
    }

    public function rates(): HasMany
    {
        return $this->hasMany(CommissionShopRate::class);
    }

    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'ratingable')->orderBy('id','desc');
    }
}
