<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CategoryDealChat extends Model
{
    use HasFactory;

    protected $fillable = ['deal_id','buyer_id'];
    protected $casts = [
        'deal_id' => 'integer',
        'buyer_id' => 'integer',
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(CategoryDeal::class, 'deal_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(CategoryDealChatMessage::class);
    }

    public function lastmsg(): HasOne
    {
        return $this->hasOne(CategoryDealChatMessage::class)->latest();
    }
}
