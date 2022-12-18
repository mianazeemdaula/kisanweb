<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CropType extends Model
{
    use HasFactory;

    protected $casts = [
        'crop_id' => 'integer',
    ];

    /**
     * Get all of the comments for the CropType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }
}
