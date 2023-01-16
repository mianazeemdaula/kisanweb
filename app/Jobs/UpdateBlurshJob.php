<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use kornrunner\Blurhash\Blurhash;
use Image;

use App\Models\Media;

class UpdateBlurshJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $media = Media::find($this->id);
        // blurHash
        $image = Image::make($media->path);
        $width = $image->width();
        $height = $image->height();
        $pixels = [];
        for ($y = 0; $y < $height; ++$y) {
            $row = [];
            for ($x = 0; $x < $width; ++$x) {
                $colors = $image->pickColor($x, $y);
                $row[] = [$colors[0], $colors[1], $colors[2]];
            }
            $pixels[] = $row;
        }
        $components_x = 4;
        $components_y = 3;
        $blurhash = Blurhash::encode($pixels, $components_x, $components_y);
        $media->blursh = $blurhash;
        $media->save();
    }
}
