<?php

use Illuminate\Support\Facades\Route;
use Appy\FcmHttpV1\FcmNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyApiEmail;
use Spatie\Sitemap\SitemapGenerator;





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
    $sitemap =  SitemapGenerator::create('https://kisanstock.com')->writeToFile("sitemap.xml");
    return \App\Helper\FCM::sendToSetting(7,"TItle", "body", ['type' => 'comment', 'shop_id'=> 3]);
    return \App\Helper\FCM::send([$token], "Title of", "Body of ",['type' => 'mand_rate', 'crop_id' => 2]);
});


Route::get('whatsapp', function(){
    return \App\Helper\WhatsApp::sendTemplate("923004103160", "hello_world", 'en_US' , [], []);
});




Route::view('/mail-view', 'reports.pdf.mail');



Route::group([], function() {

    Route::get('/login', [\App\Http\Controllers\LoginController::class,'show']);
    Route::post('/login', [\App\Http\Controllers\LoginController::class,'login'])->name('login');
    Route::get('/news',[\App\Http\Controllers\HomeController::class,'newsNotification']);
    Route::post('/news-send',[\App\Http\Controllers\HomeController::class,'sendNewsNotification']);
    Route::get('reports/rates', [\App\Http\Controllers\ReportController::class,'getCropRatePdf']);
    Route::post('reports/rates', [\App\Http\Controllers\ReportController::class,'cropRatePdf']);

    Route::get('/', function () {
        // return bcrypt('admin@#');
        return view('guest.index');
    });
    
    Route::get('/rates', function () {
        return view('guest.rates.crops');
    });
    Route::resource('deals', \App\Http\Controllers\DealController::class);
    Route::resource('commission-shops', \App\Http\Controllers\ShopController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home',[\App\Http\Controllers\HomeController::class,'index'])->name('home');
    
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::get('home',[\App\Http\Controllers\HomeController::class, 'index']);
        Route::resource('shops', \App\Http\Controllers\Admin\ShopController::class);
        Route::post('shop-stauts/{id}', [\App\Http\Controllers\Admin\ShopController::class,'updateStatus']);
        Route::resource('cities', \App\Http\Controllers\Admin\CityController::class);
        Route::resource('quotes',\App\Http\Controllers\Admin\QuoteController::class);
        Route::resource('feeds', \App\Http\Controllers\Admin\FeedController::class);
        Route::resource('deals', \App\Http\Controllers\Admin\DealController::class);
        Route::resource('settings', \App\Http\Controllers\Admin\SettingController::class);
        Route::resource('feedmills', \App\Http\Controllers\Admin\FeedMillController::class);
        Route::resource('sugarmills', \App\Http\Controllers\Admin\SugarMillController::class);
        
        // Reports
        Route::get('rate-reports',[\App\Http\Controllers\Admin\RateReportController::class,'reports']);
        Route::post('report/cropdays',[\App\Http\Controllers\Admin\RateReportController::class,'cropTypeLastDays']);
    });
});

Route::any('/webhook', [\App\Http\Controllers\Api\V1\HomeController::class,'wamessage']);
Route::any('/datafeed', [\App\Http\Controllers\DataController::class,'lastRatesUpdate']);
