<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Support\MediaOptimizer;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['path','blursh','ext'];

    protected $appends = ['thumb'];

    public function mediaable()
    {
        return $this->morphTo();
    }

    /**
     * Small copy of the photo for list cards. Falls back to the full photo
     * (and queues the thumbnail) for photos stored before thumbnails existed.
     */
    public function getThumbAttribute()
    {
        $relative = MediaOptimizer::relativePath($this->getRawOriginal('path'));
        if (!$relative || !MediaOptimizer::isImage($relative)) {
            return $this->path;
        }
        if (MediaOptimizer::hasThumb($relative)) {
            return url(MediaOptimizer::thumbPath($relative));
        }
        if ($this->exists && is_file(public_path($relative))) {
            MediaOptimizer::queue($this);
        }
        return $this->path;
    }

    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Str::startsWith($value, "http") ? $value :  url($value),
        );
    }
}
