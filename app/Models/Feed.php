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

    /** Feed ids liked by a user, loaded once per request. */
    public static array $likedByUser = [];

    public function getLikedAttribute()
    {
        $userId = auth()->id();
        if(!$userId){
            return false;
        }
        // One query for the user's likes per request, not one per post.
        self::$likedByUser[$userId] ??= array_flip(
            FeedLike::where('user_id', $userId)->pluck('feed_id')->all()
        );
        return isset(self::$likedByUser[$userId][$this->id]);
    }
}
