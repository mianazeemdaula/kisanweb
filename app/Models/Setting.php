<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Setting extends Model
{
    use HasFactory;
    // protected function getCastType($key)
    // {
    //     if ($key == 'value' && ! empty($this->type)) {
    //         return $this->type;
    //     }
    //     return parent::getCastType($key);
    // }
    
    public function users(): HasMany
    {
        return $this->hasMany(UserSetting::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }
}
