<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'name_ur',
        'slug',
        'fee',
        'duration',
        'duration_unit',
        'description',
        'description_ur',
        'active',
        'config',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_subscriptions', 'subscription_id', 'user_id')
        ->withPivot('start_date', 'end_date', 'contact', 'active', 'payment_tx_id', 'payment_gateway_id');
    }
}
