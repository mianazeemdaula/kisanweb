<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'title_ur',
        'slug',
        'image',
        'link',
        'active',
        'is_featured',
        'action',
        'action_text',
        'start_date',
        'end_date',
        'clicks',
        'impressions',
        'position',
        'location',
        'view_km',
    ];

    protected $casts = [
        'location' => Point::class,
        'active' => 'boolean',
        'is_featured' => 'boolean',
        'clicks' => 'integer',
        'impressions' => 'integer',
        'position' => 'integer',
        'view_km' => 'double',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => asset('ads/' . $value),
        );
    }

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNotExpire($query)
    {
        return $query->where('end_date', '>=', date('Y-m-d'));
    }

    public function scopeViewRange($query, $lat, $lng)
    {
        $position = new Point($lat, $lng);
        $statement = sprintf(
            "ST_Distance(location, POINT(%f, %f)) <= view_km",
            $lng,
            $lat
        );
        return $query->whereRaw($statement)->withDistance('location', $position);
    }
}
