<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['path','blursh','ext'];

    public function mediaable()
    {
        return $this->morphTo();
    }
}
