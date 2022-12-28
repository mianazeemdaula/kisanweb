<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['path','blursh','ext'];

    public function mediaable()
    {
        return $this->morphTo();
    }

    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Str::startsWith($value, "http") ? $value :  url($value),
        );
    }
}
