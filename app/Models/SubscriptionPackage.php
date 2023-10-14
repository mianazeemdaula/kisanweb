<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee',
        'duration',
        'duration_unit',
    ];
    
    protected $casts = [
        'fee' => 'integer',
        'duration' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_subscriptions', 'subscription_package_id', 'user_id')
        ->withPivot('start_date', 'end_date', 'contact', 'active', 'payment_tx_id', 'payment_gateway_id','screenshot');
    }
}
