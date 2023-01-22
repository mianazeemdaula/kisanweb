<?php

use Illuminate\Support\Facades\Route;
use Appy\FcmHttpV1\FcmNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyApiEmail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('guest.index');
});


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
    return \App\Helper\WhatsApp::sendText("923334103160","This is the message https://premium227.web-hosting.com:2083/cpsess3218210760/frontend/paper_lantern/terminal/index.html");
    return \App\Helper\WhatsApp::sendTemplate("923004103160",'welcome','en',[
        ['type'=>'text', 'text' => 'Main Azeem']
    ],[]);
 Mail::to("mazeemrehan@gmail.com")->send(new VerifyApiEmail(5656));
});

