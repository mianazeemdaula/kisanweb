<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use kornrunner\Blurhash\Blurhash;
use App\Models\Media;
use App\Models\Deal;
use App\Helper\MediaHelper;
use Image;


class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|image|mimes:jpg,jpeg,png,mp4|max:2048',
        ]);

        return MediaHelper::save($request->file('file'), Deal::find(5));
        $image = $request->file('file');
        $ext = $image->getClientOriginalExtension();
        $input['file'] = time().'.'.$ext;
        $path = public_path('/offers')."/".$input['file'];
        if(in_array($ext, ['mp4'])){

        }else{
            $image = Image::make($image->getRealPath());
            $image->save($path);
            // $imgFile->resize(150, 150, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPath.'/'.$input['file']);
            // $destinationPath = public_path('/uploads');
            // $image->move($destinationPath, $input['file']);
            
            // blurHash
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
            return ['hash' => $blurhash, 'image' => $path];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
