<?php

use Illuminate\Support\Facades\Route;
use Appy\FcmHttpV1\FcmNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyApiEmail;
use Illuminate\Support\Facades\Log;

use Spatie\Browsershot\Browsershot;
use WaAPI\WaAPI\WaAPI;
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


Route::get('/test/{id}', function($id){

    \App\Jobs\ProcessSubscriptionJob::dispatch(1, 'Test message', 'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png');
    return 'done';
    // return Browsershot::url('http://127.0.0.1:8000/save-image')
    // ->fullPage()
    // ->save('/images/crop_rates.jpg');
    $provinceWiseRates =  \DB::table('crop_rates')
    ->join('crop_types', 'crop_rates.crop_type_id', '=', 'crop_types.id')
    ->join('cities', 'crop_rates.city_id', '=', 'cities.id')
    ->join('districts', 'cities.district_id', '=', 'districts.id')
    ->join('provinces', 'districts.province_id', '=', 'provinces.id')
    ->select(
        'provinces.name as province_name',
        'crop_rates.min_price',
        'crop_rates.min_price_last',
        'crop_rates.max_price',
        'crop_rates.max_price_last',
        'crop_types.name as type_name', 
        'cities.name as city_name',
        'crop_types.id as crop_type_id',
    )
    ->whereDate('crop_rates.rate_date', '2023-06-20')
    ->where('crop_types.id', 60)
    ->get();

    $grouped = $provinceWiseRates->groupBy([
        'province_name', 
        'crop_name', 
        'type_name', 
        'city_name'
    ]);

    return $grouped;

    $tokens = \App\Models\User::whereNotNull('fcm_token')->pluck('fcm_token');
    $data = array();
    foreach ($tokens->chunk(1000) as $value) {
        $keys = $value->toArray();
        $data[] =  \App\Helper\FCM::send($keys, "منڈی ریٹ اپ ڈیٹ","فصلوں کے نئے نرخ اپ ڈیٹ ہو گئے، ابھی چیک کریں۔",['type' => 'mand_rate', 'crop_id' => 2]);
    }
    return response()->json($data, 200);
});

Route::get('test', function(){
   $packages = \App\Models\SubscriptionPackage::where('trial', true)->get();
   $res = [];
    foreach ($packages as $package) {
         foreach($package->users as $user){
            $user->pivot->start_date = now();
            $user->pivot->end_date = now()->addDays(3);
            $user->pivot->save();
            $waapi = new WaAPI();
            $res[] = $waapi->addGroupParticipant("120363168242340048@g.us",$user->pivot->contact."@c.us");
            sleep(3);
            // return response()->json($res, 200);
         }
    }
    return response()->json($res, 200);
});

Route::get('/addwa/{phone}', function($phone){
    
    // $client = new Client();
    // $client->setAuthConfig(public_path('client_secret.json'));
    // putenv('GOOGLE_APPLICATION_CREDENTIALS=client_secret.json');
    // $client->useApplicationDefaultCredentials();
    // $client->setScopes([\Google\Service\PeopleService::CONTACTS]);
    // //read all save contacts
    // // $httpClient = $client->authorize();
    // // $response = $httpClient->get('https://www.googleapis.com/plus/v1/people/me');
    // // return $response->getStatusCode();
    // // return $httpClient;
    // // // Add contact to Google Contacts
    // $service = new \Google_Service_PeopleService($client);
    // $contact = new \Google_Service_PeopleService_Person();
    // $phone = new \Google_Service_PeopleService_PhoneNumber();
    // $phone->setValue("+923334103160");
    // $phone->setType('mobile');
    // $contact->setPhoneNumbers($phone);
    // $contact->setNames(['givenName' => 'Azeem Ufone', 'familyName' => 'Azeem Ufone']);
    // $res = $service->people->createContact($contact);
    // return response()->json($res, 200);
    $waapi = new WaAPI();
    // $res = $waapi->sendMessage("$phone@c.us","Oh Kida");
    // // $res = $waapi->deleteMessageById("true_923004103160@c.us_3EB0119B4DBC8915927FF5");
    $res = $waapi->addGroupParticipant("120363168242340048@g.us",$phone."@c.us");
    return response()->json($res, 200);
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
        Route::get('deals-export', [\App\Http\Controllers\Admin\DealController::class,'export']);
        Route::get('export-nearby-shops/{id}', [\App\Http\Controllers\Admin\DealController::class,'exportNearBuyers']);
        Route::resource('settings', \App\Http\Controllers\Admin\SettingController::class);
        Route::resource('feedmills', \App\Http\Controllers\Admin\FeedMillController::class);
        Route::resource('feedmillsrate', \App\Http\Controllers\Admin\FeedMillRateController::class);
        Route::resource('sugarmills', \App\Http\Controllers\Admin\SugarMillController::class);
        Route::resource('sugarmillsrate', \App\Http\Controllers\Admin\SugarMillRateController::class);

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
    });
});

Route::any('/webhook', [\App\Http\Controllers\Api\V1\HomeController::class,'wamessage']);
Route::get('/datafeed', [\App\Http\Controllers\DataController::class,'paymentAndSubscription']);
Route::get('/rate-image', [\App\Http\Controllers\DataController::class,'generateRatesImage']);


Route::any('whtasapphooks', function (Request $request) {
    Log::debug($request->all());
});
