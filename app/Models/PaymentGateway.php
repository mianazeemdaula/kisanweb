<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ur',
        'slug',
        'logo',
        'active',
        'config',
    ];

    protected $casts = [
        'config' => 'json',
    ];
}
