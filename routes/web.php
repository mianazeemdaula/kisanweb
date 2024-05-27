<?php

use Illuminate\Support\Facades\Route;
use Appy\FcmHttpV1\FcmNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyApiEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Google\Client;



Route::get('app/terms-and-conditions', function () {
    return view('app.terms');
});

Route::get('app/privacy-policy', function () {
    return view('app.privacy');
});

Route::get('app/fb-delete-data', function () {
    return response()->json(['email' => 'abc@gmail.com'], 200);
});

Route::get('save-image',[\App\Http\Controllers\ReportController::class,'saveImage']);


Route::view('/mail-view', 'reports.pdf.mail');

Route::group([], function() {

    Route::get('/login', [\App\Http\Controllers\LoginController::class,'show']);
    Route::post('/login', [\App\Http\Controllers\LoginController::class,'login'])->name('login');
    Route::get('/news',[\App\Http\Controllers\HomeController::class,'newsNotification']);
    Route::post('/news-send',[\App\Http\Controllers\HomeController::class,'sendNewsNotification']);
    Route::get('reports/rates', [\App\Http\Controllers\ReportController::class,'getCropRatePdf']);
    Route::post('reports/rates', [\App\Http\Controllers\ReportController::class,'cropRatePdf']);
    Route::post('reports/sugar-mill-report', [\App\Http\Controllers\ReportController::class,'sugarMillReport']);
    Route::post('reports/fee-mill-report', [\App\Http\Controllers\ReportController::class,'feedMillReport']);
    

    Route::get('/', function () {
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
        Route::get('deals-export', [\App\Http\Controllers\Admin\DealController::class,'export']);
        Route::get('export-nearby-shops/{id}', [\App\Http\Controllers\Admin\DealController::class,'exportNearBuyers']);
        Route::resource('settings', \App\Http\Controllers\Admin\SettingController::class);
        Route::resource('feedmills', \App\Http\Controllers\Admin\FeedMillController::class);
        Route::resource('feedmillsrate', \App\Http\Controllers\Admin\FeedMillRateController::class);
        Route::resource('sugarmills', \App\Http\Controllers\Admin\SugarMillController::class);
        Route::resource('sugarmillsrate', \App\Http\Controllers\Admin\SugarMillRateController::class);

        // Deo Crop Rates
        Route::resource('deorates', \App\Http\Controllers\Admin\RatesDeoController::class);

        // WhatsApp
        Route::get('send-message', [\App\Http\Controllers\Admin\WAMessageController::class,'sendMessage']);
        Route::post('send-message', [\App\Http\Controllers\Admin\WAMessageController::class,'postSendMessage']);
        Route::get('send-group-message', [\App\Http\Controllers\Admin\WAMessageController::class,'getGroups']);
        Route::post('send-group-message', [\App\Http\Controllers\Admin\WAMessageController::class,'sendGroupMessage']);
        Route::get('del-wa-group-file', [\App\Http\Controllers\Admin\WAMessageController::class,'deleteWAGroupFile']);
        
        // Reports
        Route::get('rate-reports',[\App\Http\Controllers\Admin\RateReportController::class,'reports']);
        Route::post('report/cropdays',[\App\Http\Controllers\Admin\RateReportController::class,'cropTypeLastDays']);

        // Subscriptions
        Route::resource('subscriptions',\App\Http\Controllers\Admin\SubscriptionController::class);
        Route::resource('subscriptions.packages',\App\Http\Controllers\Admin\SubscriptionPackageController::class);
        Route::resource('pending-subscriptions',\App\Http\Controllers\Admin\SubscriptionPendingController::class);
        Route::get('subscription-contacts',[\App\Http\Controllers\Admin\SubscriptionController::class,'exportContacts']);

        // Advertisement
        Route::resource('ads',\App\Http\Controllers\Admin\AdsController::class);

        // 
        Route::resource('support',\App\Http\Controllers\Admin\SupportController::class);
        Route::resource('support.chat',\App\Http\Controllers\Admin\SupportChatController::class);

        // statistics
        Route::get('statistics/today-rates-users',[\App\Http\Controllers\Admin\StatisticsController::class,'todayCropRatesDeos']);
    });
});

Route::any('/webhook', [\App\Http\Controllers\Api\V1\HomeController::class,'wamessage']);
Route::get('/datafeed', [\App\Http\Controllers\DataController::class,'paymentAndSubscription']);
Route::get('/rate-image', [\App\Http\Controllers\DataController::class,'generateRatesImage']);


Route::any('whtasapphooks', function (Request $request) {
    return \App\Helper\WhatsApp::sendText('923004103160','This is a test message with webhook');
    Log::debug($request->all());
});

Route::get('remove-user', function(){
    return view('auth.deleteuser');
});


Route::post('remove-user', function(Request $request){
    $request->validate([
        'email'=> 'required'
    ]);
    $user = \App\Models\User::where('email',$request->email)->first();
    if(!$user){
        return redirect()->back()->WithErrors(['email' => 'User not found']);
    }
    \Mail::to($user->email)->send(new \App\Mail\DeleteAccountEmail($user->id));
    return redirect()->back()->WithErrors(['message' => 'Email sent to user with confirmation link']);
});

Route::get('delete-account/{id}', function($id){
    $user = \App\Models\User::find($id);
    if($user){
        $user->delete();
    }else{
        return view('auth.userdeleted',['deleted' =>false]);
    }
    return view('auth.userdeleted',['deleted' =>true]);
});