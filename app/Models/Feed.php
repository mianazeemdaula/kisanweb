<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;
    protected $appends = ['liked'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(FeedLike::class);
    }

    public function comments()
    {
        return $this->hasMany(FeedComment::class);
    }

    public function scopeWithCounts($query)
    {
        return $query->withCount(['likes', 'comments']);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    public function getLikedAttribute()
    {
        if(auth()->id())
        return $this->likes()->where('user_id',auth()->id())->exists();
        return false;
    }
}
