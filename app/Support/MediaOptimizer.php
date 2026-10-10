<?php

namespace App\Support;

use App\Jobs\OptimizeMediaJob;
use App\Models\Media;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

/**
 * Keeps uploaded photos light without visibly losing quality.
 *
 *  - The stored photo is capped at MAX_SIDE px and saved as a JPEG. Phone
 *    screens are at most ~1440px wide, so nothing sharper is ever displayed.
 *  - A THUMB_SIDE px copy is kept next to it in a "thumbs" folder for list
 *    cards, where the full photo was being downloaded to fill a small tile.
 *
 * Both are plain files under public/, served directly by the web server.
 */
class MediaOptimizer
{
    const MAX_SIDE = 1280;
    const QUALITY = 80;
    const THUMB_SIDE = 480;
    const THUMB_QUALITY = 72;

    /** A well-compressed photo is far below this many bytes per pixel. */
    const HEAVY_BYTES_PER_PIXEL = 0.30;

    /** Path relative to public/ for a stored media path, or null if it is not a local file. */
    public static function relativePath(?string $raw): ?string
    {
        if (!$raw) {
            return null;
        }
        // old rows hold absolute URLs (http://127.0.0.1:8000/offers/x.jpg)
        $path = preg_match('#^https?://#i', $raw) ? (string) parse_url($raw, PHP_URL_PATH) : $raw;
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path === '' || str_contains($path, '..')) {
            return null;
        }

        return $path;
    }

    public static function isImage(string $relative): bool
    {
        return in_array(strtolower(pathinfo($relative, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']);
    }

    /** Where the thumbnail of a photo lives: offers/abc.png -> offers/thumbs/abc.jpg */
    public static function thumbPath(string $relative): string
    {
        $dir = trim(str_replace('\\', '/', dirname($relative)), './');

        return ($dir === '' ? '' : $dir.'/').'thumbs/'.pathinfo($relative, PATHINFO_FILENAME).'.jpg';
    }

    public static function hasThumb(string $relative): bool
    {
        return is_file(public_path(self::thumbPath($relative)));
    }

    /**
     * Shrink and re-encode a stored photo if it is larger than it needs to be.
     * Returns the path it ends up at (a PNG becomes a .jpg).
     */
    public static function optimizeFile(string $relative): string
    {
        $source = public_path($relative);
        if (!self::isImage($relative) || !is_file($source)) {
            return $relative;
        }
        self::roomForLargePhotos();

        $image = Image::make($source);
        $bytes = filesize($source);
        $isJpeg = in_array(strtolower(pathinfo($relative, PATHINFO_EXTENSION)), ['jpg', 'jpeg']);
        $tooBig = max($image->width(), $image->height()) > self::MAX_SIDE;
        $heavy = $bytes > $image->width() * $image->height() * self::HEAVY_BYTES_PER_PIXEL;
        if ($isJpeg && !$tooBig && !$heavy) {
            $image->destroy();

            return $relative;
        }

        self::upright($image);
        if ($tooBig) {
            $image->resize(self::MAX_SIDE, self::MAX_SIDE, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        $target = preg_replace('/\.[^.\/]+$/', '.jpg', $relative);
        $temp = public_path($target).'.tmp';
        self::flatten($image)->save($temp, self::QUALITY, 'jpg');
        $image->destroy();

        // Never replace a photo with a bigger file of the same dimensions.
        if (!$tooBig && $isJpeg && filesize($temp) >= $bytes) {
            @unlink($temp);

            return $relative;
        }
        rename($temp, public_path($target));
        if ($target !== $relative) {
            @unlink($source);
        }

        return $target;
    }

    /** Write the list thumbnail for a stored photo. */
    public static function makeThumb(string $relative): bool
    {
        $source = public_path($relative);
        if (!self::isImage($relative) || !is_file($source)) {
            return false;
        }
        self::roomForLargePhotos();

        $destination = public_path(self::thumbPath($relative));
        if (!is_dir(dirname($destination))) {
            @mkdir(dirname($destination), 0755, true);
        }
        $image = Image::make($source);
        self::upright($image);
        $image->resize(self::THUMB_SIDE, self::THUMB_SIDE, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        self::flatten($image)->save($destination, self::THUMB_QUALITY, 'jpg');
        $image->destroy();

        return true;
    }

    /** Optimize one media row's photo and thumbnail, updating the row if the file was renamed. */
    public static function process(Media $media): void
    {
        $relative = self::relativePath($media->getRawOriginal('path'));
        if (!$relative || !self::isImage($relative) || !is_file(public_path($relative))) {
            return;
        }
        $optimized = self::optimizeFile($relative);
        self::makeThumb($optimized);
        if ($optimized !== $relative) {
            $media->path = $optimized;
            $media->ext = 'jpg';
            $media->save();
        }
    }

    /**
     * Photos uploaded before this existed have no thumbnail. Queue the work
     * once per photo; until it is done the list shows the full photo as before.
     */
    public static function queue(Media $media): void
    {
        try {
            if (Cache::add('media_optimize:'.$media->id, 1, 1800)) {
                OptimizeMediaJob::dispatch($media->id);
            }
        } catch (\Throwable $e) {
            Log::warning('Could not queue media optimization', ['media' => $media->id, 'error' => $e->getMessage()]);
        }
    }

    /** Phone cameras store portrait shots sideways with a rotation flag. */
    private static function upright($image): void
    {
        try {
            $image->orientate();
        } catch (\Throwable $e) {
            // no EXIF support or no EXIF data: keep the pixels as they are
        }
    }

    /** JPEG has no transparency: put transparent PNGs on white instead of black. */
    private static function flatten($image)
    {
        if ($image->mime() !== 'image/png') {
            return $image;
        }

        return Image::canvas($image->width(), $image->height(), '#ffffff')->insert($image, 'top-left');
    }

    private static function roomForLargePhotos(): void
    {
        // a 12 megapixel photo needs ~100MB decoded
        @ini_set('memory_limit', '512M');
    }
}
