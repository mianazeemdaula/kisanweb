<?php

use Illuminate\Support\Facades\Route;
use Appy\FcmHttpV1\FcmNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyApiEmail;


use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CityController;

Route::get('/login', [LoginController::class,'show']);
Route::post('/login', [LoginController::class,'login'])->name('login');


Route::get('/', function () {
    // return bcrypt('admin@#');
    return view('guest.index');
});

Route::get('/rates', function () {
    return view('guest.rates.crops');
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
    $tokens = \App\Models\User::whereNotNull('fcm_token')->pluck('fcm_token');
    $data = array();
    foreach ($tokens->chunk(1000) as $value) {
        $keys = $value->toArray();
        $data[] =  \App\Helper\FCM::send($keys, "منڈی ریٹ اپ ڈیٹ","فصلوں کے نئے نرخ اپ ڈیٹ ہو گئے، ابھی چیک کریں۔",['type' => 'mand_rate', 'crop_id' => 2]);
    }
    return response()->json($data, 200);
});

Route::get('/not/{token}', function($token){

    return \App\Helper\FCM::send([$token], "Title of", "Body of ",['type' => 'mand_rate', 'crop_id' => 2]);
});

Route::get('/statistics', function(){
    $data['users'] = \App\Models\User::whereHas('addresses')->count();
    $data['last_day'] = \App\Models\User::whereHas('addresses')->whereDate('created_at',now()->subDays(1))->count();
    $data['deals'] = \App\Models\Deal::where('status','open')->count();
    $today = \App\Models\User::whereHas('addresses')->whereDate('created_at',now())->with(['addresses' => function($e){
        $e->select('id','user_id','address');
    }])
    ->select('id','name','mobile')
    ->orderBy('created_at','desc')->get();
    $data['today_count'] = $today->count();
    $data['today'] = $today;
    return response()->json($data, 200, [], JSON_PRETTY_PRINT);
});

Route::get('send-notification/{id}', function($id){
    return \App\Helper\FCM::sendNotification(\App\Models\Notification::find($id));
});

Route::get('whatsapp', function(){
    return \App\Helper\WhatsApp::sendTemplate("923004103160", "hello_world", 'en_US' , [], []);
});


Route::get('/news',[HomeController::class,'newsNotification']);
Route::post('/news-send',[HomeController::class,'sendNewsNotification']);

Route::get('reports/rates', [ReportController::class,'getCropRatePdf']);
Route::post('reports/rates', [ReportController::class,'cropRatePdf']);

Route::view('/mail-view', 'reports.pdf.mail');



Route::middleware(['auth'])->group(function () {
    Route::get('/home',[HomeController::class,'index'])->name('home');
    
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::get('home',[HomeController::class, 'index']);
        Route::resource('shops', ShopController::class);
        Route::post('shop-stauts/{id}', [ShopController::class,'updateStatus']);
        Route::resource('cities', CityController::class);
        Route::resource('quotes',App\Http\Controllers\Admin\QuoteController::class);
        Route::resource('feeds', App\Http\Controllers\Admin\FeedController::class);
        Route::resource('deals', App\Http\Controllers\Admin\DealController::class);
        Route::resource('settings', App\Http\Controllers\Admin\SettingController::class);
        
        // Reports
        Route::get('rate-reports',[App\Http\Controllers\Admin\RateReportController::class,'reports']);
        Route::post('report/cropdays',[App\Http\Controllers\Admin\RateReportController::class,'cropTypeLastDays']);
    });
});

Route::resource('deals', DealController::class);
// Route::resource('rates', DealController::class);