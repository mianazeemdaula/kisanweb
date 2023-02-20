<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'name_ur','code','district_id'];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function rate(): HasOne
    {
        return $this->hasOne(CropRate::class);
    }
}
