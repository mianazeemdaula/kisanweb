<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Media;
use App\Support\MediaOptimizer;

class OptimizeMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:optimize {--limit=0 : Stop after this many photos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shrink stored deal and post photos and create their list thumbnails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit = (int) $this->option('limit');
        $done = 0;
        $saved = 0;
        $failed = 0;
        foreach (Media::orderBy('id')->cursor() as $media) {
            $relative = MediaOptimizer::relativePath($media->getRawOriginal('path'));
            if (!$relative || !MediaOptimizer::isImage($relative) || !is_file(public_path($relative))) {
                continue;
            }
            if (MediaOptimizer::hasThumb($relative)) {
                continue;
            }
            $before = filesize(public_path($relative));
            try {
                MediaOptimizer::process($media);
                $after = MediaOptimizer::relativePath($media->getRawOriginal('path'));
                clearstatcache();
                $saved += $before - filesize(public_path($after));
                $done++;
            } catch (\Throwable $e) {
                $failed++;
                $this->warn("media {$media->id} ({$relative}): ".$e->getMessage());
            }
            if ($limit && $done >= $limit) {
                break;
            }
        }
        $this->info("Optimized {$done} photos, saved ".round($saved / 1048576, 1)." MB, {$failed} failed.");

        return 0;
    }
}
