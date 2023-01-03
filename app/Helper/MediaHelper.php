<?php
namespace App\Helper;

use Illuminate\Support\Str;

use kornrunner\Blurhash\Blurhash;
use App\Models\Media;

use Image;

class MediaHelper {
    static public function save($file, $model,$path = 'offers')
    {
        $ext = $file->getClientOriginalExtension();
        $fileName = $model->id."_".Str::random(10).'.'.$ext;
        $path = "$path/".$fileName;
        $blurhash = "LrJaflIUENE1_2RjRQR*?wM{V?ad";
        if(in_array($ext, ['mp4'])){
            $file->store('videos', $path);
        }else{
            $image = Image::make($file->getRealPath());
            $image->save($path);
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
        $model->media()->save($media);
        return ['hash' => $blurhash, 'image' => $path];
    }
}