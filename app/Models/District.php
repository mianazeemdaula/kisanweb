<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class District extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'name_ur', 'code','province_id'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
