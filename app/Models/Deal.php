<?php

namespace App\Models;

use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use \JordanMiguel\LaravelPopular\Traits\Visitable;


class Deal extends Model
{
    use HasFactory, Visitable;
    
    protected $casts = [
        'location' => Point::class,
        'crop_type_id' => 'integer',
        'seller_id' => 'integer',
        'packing_id' => 'integer',
        'demand' => 'integer',
        'qty' => 'integer',
    ];

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    /**
     * Get the user that owns the Offer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the user that owns the Offer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function packing()
    {
        return $this->belongsTo(Packing::class, 'packing_id');
    }

    /**
     * Get the user that owns the Offer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(CropType::class, 'crop_type_id');
    }
    
    /**
     * Get all of the comments for the Offer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }
}
