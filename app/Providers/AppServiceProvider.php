<?php

namespace App\Providers;

use App\Support\ApiCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

// use Illuminate\Database\Events\QueryExecuted;
// use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerApiCacheInvalidation();
        // DB::listen(function(QueryExecuted $query) {
        //     File::append(
        //         storage_path('/logs/query.log'),
        //         $query->sql . ' [' . implode(', ', $query->bindings) . ']' . '[' . $query->time . ']' . PHP_EOL
        //    );
        // });
    }

    /**
     * Cached API lists (App\Support\ApiCache) are dropped the moment the
     * data behind them changes, so they never serve a stale bid, rate or deal.
     */
    private function registerApiCacheInvalidation(): void
    {
        $groups = [
            \App\Models\Deal::class => ['deals'],
            \App\Models\Bid::class => ['deals'],
            \App\Models\Reaction::class => ['deals'],
            \App\Models\CategoryDeal::class => ['cat_deals'],
            \App\Models\CategoryDealBid::class => ['cat_deals'],
            \App\Models\CategoryDealReaction::class => ['cat_deals'],
            \App\Models\Media::class => ['deals', 'cat_deals'],
            \App\Models\CropRate::class => ['rates'],
            \App\Models\Crop::class => ['crops', 'rates'],
            \App\Models\CropType::class => ['crops', 'rates'],
            \App\Models\City::class => ['cities', 'rates'],
            \App\Models\Category::class => ['categories', 'cat_deals'],
            \App\Models\SubCategory::class => ['categories', 'cat_deals'],
            \App\Models\WeightType::class => ['static', 'deals', 'cat_deals'],
            \App\Models\Packing::class => ['static', 'deals', 'cat_deals'],
        ];
        foreach ($groups as $model => $names) {
            $flush = fn () => ApiCache::flush(...$names);
            $model::saved($flush);
            $model::deleted($flush);
        }

        // Sellers and bidders are embedded in the deal lists.
        \App\Models\User::updated(function ($user) {
            if ($user->wasChanged(['name', 'image', 'mobile', 'whatsapp'])) {
                ApiCache::flush('deals', 'cat_deals');
            }
        });
        \App\Models\User::deleted(fn () => ApiCache::flush('deals', 'cat_deals'));

        $forgetRating = function ($review) {
            Cache::forget('user_rating:'.$review->user_id);
            unset(\App\Models\User::$ratings[$review->user_id]);
            ApiCache::flush('deals', 'cat_deals');
        };
        \App\Models\Review::saved($forgetRating);
        \App\Models\Review::deleted($forgetRating);

        $forgetLikes = function ($like) {
            unset(\App\Models\Feed::$likedByUser[$like->user_id]);
        };
        \App\Models\FeedLike::saved($forgetLikes);
        \App\Models\FeedLike::deleted($forgetLikes);
    }
}
