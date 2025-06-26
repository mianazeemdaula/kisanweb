<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryDeal extends Model
{
    use HasFactory;

    protected $appends = ['reactionsIds'];

    protected $casts = [
        'attr' => 'array',
        'location' => Point::class,
    ];

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    // belongsToMany relationship with Category
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }

    // belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function packing()
    {
        return $this->belongsTo(Packing::class, 'packing_id');
    }

    public function weight()
    {
        return $this->belongsTo(WeightType::class, 'weight_type_id');
    }


    //bids
    public function bids()
    {
        return $this->hasMany(CategoryDealBid::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(CategoryDealReaction::class);
    }

    public function getReactionsIdsAttribute()
    {
        return $this->reactions()->pluck('user_id');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }
}
