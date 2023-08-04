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



Route::group(['namespace' => 'App\Http\Controllers'], function() {

    
    Route::get('/login', [LoginController::class,'show']);
    Route::post('/login', [LoginController::class,'login'])->name('login');
    Route::get('/news',[HomeController::class,'newsNotification']);
    Route::post('/news-send',[HomeController::class,'sendNewsNotification']);

    Route::get('reports/rates', [ReportController::class,'getCropRatePdf']);
    Route::post('reports/rates', [ReportController::class,'cropRatePdf']);

    Route::get('/', function () {
        // return bcrypt('admin@#');
        return view('guest.index');
    });
    
    Route::get('/rates', function () {
        return view('guest.rates.crops');
    });
    
    Route::get('data/cities', [DataController::class,'cities']);
    Route::resource('deals', DealController::class);
    Route::resource('shops', DealController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home',[HomeController::class,'index'])->name('home');
    
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'App\Http\Controllers\Admin'], function() {
        Route::get('home',[HomeController::class, 'index']);
        Route::resource('shops', ShopController::class);
        Route::post('shop-stauts/{id}', [ShopController::class,'updateStatus']);
        Route::resource('cities', CityController::class);
        Route::resource('quotes',QuoteController::class);
        Route::resource('feeds', FeedController::class);
        Route::resource('deals', DealController::class);
        Route::resource('settings', SettingController::class);
        
        // Reports
        Route::get('rate-reports',[RateReportController::class,'reports']);
        Route::post('report/cropdays',[RateReportController::class,'cropTypeLastDays']);
    });
});
