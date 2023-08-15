<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeedMill extends Model
{
    use HasFactory;

    protected $casts = [
        'active' => 'boolean',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(FeedMillRate::class);
    }

    public function rate(): HasOne
    {
        return $this->hasOne(FeedMillRate::class);
    }
}
