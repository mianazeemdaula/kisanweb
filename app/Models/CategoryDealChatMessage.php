<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CategoryDealChatMessage extends Model
{
    use HasFactory;

    protected $casts = [
        'chat_id' => 'integer',
        'sender_id' => 'integer',
        'type' => 'integer',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(CategoryDealChat::class, 'sender_id');
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(CategoryDealChat::class, 'chat_id');
    }
}
