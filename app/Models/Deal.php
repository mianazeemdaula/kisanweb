<?php

namespace App\Models;

use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use \JordanMiguel\LaravelPopular\Traits\Visitable;


class Deal extends Model
{
    use HasFactory, Visitable;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    
    protected $casts = [
        'location' => Point::class,
        'crop_type_id' => 'integer',
        'seller_id' => 'integer',
        'accept_bid_id' => 'integer',
        'packing_id' => 'integer',
        'demand' => 'float',
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

    public function weight()
    {
        return $this->belongsTo(WeightType::class, 'weight_type_id');
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

    public function crops()
    {
        return $this->belongsToThrough(Crop::class,CropType::class);
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

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    
    public function acceptedBid(): HasOne
    {
        return $this->hasOne(Bid::class, 'id','accept_bid_id');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }
}
