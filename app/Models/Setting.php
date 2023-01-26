<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
