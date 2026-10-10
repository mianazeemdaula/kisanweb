<?php

namespace App\Support;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Cache for API read endpoints whose response is the same for every user.
 *
 * Entries belong to a group ("deals", "rates", ...). Each group has a version
 * number that is part of every key, so flushing a group is one write: bump the
 * version and all of its old entries are simply never read again (they expire
 * on their own). This works on any cache store, so it needs no Redis tags.
 *
 * Groups are flushed from model events (see AppServiceProvider); the TTL is the
 * safety net for changes that bypass Eloquent.
 */
class ApiCache
{
    /** Versions read during this request, so a page of lookups costs one read per group. */
    private static array $versions = [];

    /**
     * A JSON response built once and then served from the cache as a ready
     * string: no queries, no model hydration and no re-encoding on a hit.
     */
    public static function json(string $group, string $key, int $ttl, Closure $build)
    {
        $json = self::remember($group, $key, $ttl, function () use ($build) {
            $data = $build();
            return is_string($data) ? $data : json_encode($data);
        });

        return response($json, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Cached value for a group. If the cache store is unreachable the value is
     * built directly, so a cache outage slows the API down but never breaks it.
     */
    public static function remember(string $group, string $key, int $ttl, Closure $build)
    {
        try {
            $cacheKey = 'api:'.$group.':'.self::version($group).':'.$key;
            $hit = Cache::get($cacheKey);
            if ($hit !== null) {
                return $hit;
            }
        } catch (\Throwable $e) {
            Log::warning('ApiCache read failed', ['group' => $group, 'error' => $e->getMessage()]);
            return $build();
        }

        $value = $build();

        try {
            Cache::put($cacheKey, $value, $ttl);
        } catch (\Throwable $e) {
            Log::warning('ApiCache write failed', ['group' => $group, 'error' => $e->getMessage()]);
        }

        return $value;
    }

    /** Drop everything cached for the given groups. */
    public static function flush(string ...$groups): void
    {
        foreach ($groups as $group) {
            try {
                $version = (int) (microtime(true) * 1000);
                Cache::forever('api:version:'.$group, $version);
                self::$versions[$group] = $version;
            } catch (\Throwable $e) {
                Log::warning('ApiCache flush failed', ['group' => $group, 'error' => $e->getMessage()]);
            }
        }
    }

    /** Key part made from the request inputs that change the result. */
    public static function key(Request $request, array $inputs): string
    {
        $parts = [];
        foreach ($inputs as $name) {
            $value = $request->input($name);
            $parts[$name] = is_scalar($value) || $value === null ? (string) $value : json_encode($value);
        }

        return md5(json_encode($parts));
    }

    private static function version(string $group): int
    {
        return self::$versions[$group] ??= (int) Cache::get('api:version:'.$group, 1);
    }
}
