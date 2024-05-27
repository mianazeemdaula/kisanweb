<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SugarMillRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sugar_mill_id',
        'min_price',
        'max_price',
        'min_price_last',
        'max_price_last',
    ];

    public function sugarMill()
    {
        return $this->belongsTo(SugarMill::class);
    }
}
