<?php

use Illuminate\Support\Facades\Route;
use Appy\FcmHttpV1\FcmNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyApiEmail;


use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DataController;

Route::get('/login', [LoginController::class,'show']);
Route::post('/login', [LoginController::class,'login'])->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/home',[HomeController::class,'index'])->name('home');
});

Route::get('/', function () {
    return view('guest.index');
});

Route::get('data/cities', [DataController::class,'cities']);

Route::get('app/terms-and-conditions', function () {
    return view('app.terms');
});

Route::get('app/privacy-policy', function () {
    return view('app.privacy');
});

Route::get('app/fb-delete-data', function () {
    return response()->json(['email' => 'abc@gmail.com'], 200);
});


Route::get('/test/{id}', function($id){

    $data = App\Models\CropRate::select(
        'rate_date','crop_type_id',
        \DB::raw('cast(min(min_price) as float) as min_rate'),
        \DB::raw('cast(max(max_price) as float) as max_rate'),
    )->groupBy('rate_date','crop_type_id')->get();
    return response()->json($data, 200, [],JSON_PRETTY_PRINT);
    return App\Models\CropRate::leftJoin('crop_rates', function($join) { 
            $join->on('crop_rates.id', '=', \DB::raw("(SELECT avg(min_rate) from crop_rates WHERE crop_rates.rate_date = crop_rates.rate_date)"));
        })
        // ->orderBy('messages.created_at','desc')
        ->select('crop_rates.*')
        // ->whereHas('lastmsg')
        ->where('crop_rates.crop_type_id',$id)->get();
    return \App\Helper\WhatsApp::sendText("923004103160","This is the message https://kisanstock.com/deal/5");
    return \App\Helper\WhatsApp::sendTemplate("923004103160",'hello_world','en_US',[
        // ['type'=>'text', 'text' => 'Main Azeem']
    ],[]);
 Mail::to("mazeemrehan@gmail.com")->send(new VerifyApiEmail(5656));
});

