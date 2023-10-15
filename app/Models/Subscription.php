<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'name_ur',
        'slug',
        'description',
        'description_ur',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
    

    public function scopeActive($query)
    {
        return $query->whereActive(true);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(SubscriptionPackage::class)->orderBy('fee');
    }
}
