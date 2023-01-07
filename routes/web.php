<?php

use Illuminate\Support\Facades\Route;
use Appy\FcmHttpV1\FcmNotification;

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
    return view('welcome');
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
//  \App\Jobs\CreateDealJob::dispatch($id);
$data = \App\Models\Notification::all();
foreach ($data as $value) {
    \App\Helper\FCM::sendNotification($value);
}
 return;
$token = "eb50Ms5bSO-TMcouiXHC21:APA91bHBZau81GnRuYSy8FzG_9DEmd4y2KtWCh4aUKMUWgXDFGK48G3cugXuR82AscF77Nv93ky55zK4k7Tm9vOlv6ZmwlNXMRs8LZo8MHWSKWi0Or82LgfegF1CFI5HpbJG4xBy04FV";
$notif = new FcmNotification();
// return $notif->setTitle("Title")->setBody("Message here")->setToken($token)->setClickAction("/news")->send(); 
return \App\Helper\FCM::send([$token],"Title of ", "Body of",(array) []);
});

