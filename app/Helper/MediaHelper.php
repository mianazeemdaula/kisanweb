<?php
namespace App\Helper;

use Illuminate\Support\Str;

use kornrunner\Blurhash\Blurhash;
use App\Models\Media;
use App\Support\MediaOptimizer;
use App\Jobs\UpdateBlurshJob;

use Image;

class MediaHelper {
    static public function save($file, $model,$path = 'offers')
    {
        $ext = $file->getClientOriginalExtension();
        $fileName = Str::random(15).'.'.$ext;
        $path = "$path/".$fileName;
        $blurhash = "LrJaflIUENE1_2RjRQR*?wM{V?ad";
        if(in_array($ext, ['mp4'])){
            $file->store('videos', $path);
        }else{
            $image = Image::make($file->getRealPath());
            $image->save($path);
            // Cap the size, store as JPEG and write the list thumbnail. If
            // that fails for any reason the upload still succeeds as before.
            try {
                $path = MediaOptimizer::optimizeFile($path);
                MediaOptimizer::makeThumb($path);
                $ext = pathinfo($path, PATHINFO_EXTENSION);
            } catch (\Throwable $e) {
                \Log::warning('Media optimization failed', ['path' => $path, 'error' => $e->getMessage()]);
            }
            // $imgFile->resize(150, 150, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPath.'/'.$input['file']);
            // $destinationPath = public_path('/uploads');
            // $image->move($destinationPath, $input['file']);
            
            // blurHash
            // $width = $image->width();
            // $height = $image->height();
            // $pixels = [];
            // for ($y = 0; $y < $height; ++$y) {
            //     $row = [];
            //     for ($x = 0; $x < $width; ++$x) {
            //         $colors = $image->pickColor($x, $y);
            //         $row[] = [$colors[0], $colors[1], $colors[2]];
            //     }
            //     $pixels[] = $row;
            // }
            // $components_x = 4;
            // $components_y = 3;
            // $blurhash = Blurhash::encode($pixels, $components_x, $components_y);
        }
        $media = new Media([
            'path' => $path,
            'blursh' => $blurhash,
            'ext' => $ext
        ]);
        return $media;
        // $model->media()->save($media);
        // UpdateBlurshJob::dispatchAfterResponse($media->id);
        // return ['hash' => $blurhash, 'image' => $path];
    }
}