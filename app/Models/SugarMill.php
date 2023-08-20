<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SugarMill extends Model
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
        return $this->hasMany(SugarMillRate::class);
    }
    
    public function rate(): HasOne
    {
        return $this->hasOne(SugarMillRate::class)->lastest();
    }
}
