<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\CropController;
use App\Http\Controllers\Api\V1\CropTypeController;
use App\Http\Controllers\Api\V1\DealController;
use App\Http\Controllers\Api\V1\BidController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MediaController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\InboxController;
use App\Http\Controllers\Api\V1\CategoryInboxController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\ReactionController;
use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\CropRateController;
use App\Http\Controllers\Api\V1\CropCityRateController;
use App\Http\Controllers\Api\V1\DataController;
use App\Http\Controllers\Api\V1\CommissionShopController;
use App\Http\Controllers\Api\V1\ShopFavoriteController;
use App\Http\Controllers\Api\V1\ShopReportController;
use App\Http\Controllers\Api\V1\QuoteController;
use App\Http\Controllers\Api\V1\VideoController;
use App\Http\Controllers\Api\V1\Feed\FeedController;
use App\Http\Controllers\Api\V1\Feed\FeedLikeController;
use App\Http\Controllers\Api\V1\Feed\FeedCommentController;
use App\Http\Controllers\Api\V1\RatingController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\FeedMill\FeedMillController;
use App\Http\Controllers\Api\V1\SugarMill\SugarMillController;
use App\Http\Controllers\Api\V1\Support\SupportController;
use App\Http\Controllers\Api\V1\Support\SupportDetailController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\Category\CategoryController;
use App\Http\Controllers\Api\V1\Category\CategoryDealsController;
use App\Http\Controllers\Api\V1\Category\CategoryDealsBidController;
use App\Http\Controllers\Api\V1\Category\CategoryDealReactionController;
use App\Http\Controllers\Api\V1\PaymentGatewaysController;
use App\Http\Controllers\Api\V1\AdsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/phone-login', [AuthController::class,'login']);
Route::post('auth/phone-signup', [AuthController::class,'phoneSignup']);
Route::post('auth/mobile-register', [AuthController::class,'mobileRegister']);
Route::post('auth/loginsocial', [AuthController::class,'loginFromSocial']);
Route::post('auth/{provider}/callback', [AuthController::class,'socialcallback']);
Route::post('auth/whtasapp', [AuthController::class,'whatsapp']);

Route::resource('crop', CropController::class);
Route::resource('crop.type', CropTypeController::class);


Route::resource('media', MediaController::class);
Route::get('/cities',[CityController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::get('crops', [HomeController::class,'crops']);
    Route::post('popular', [HomeController::class,'popular']);
    Route::get('latest', [HomeController::class,'latest']);

    // categories deals and data
    Route::post('catdeals', [HomeController::class,'catdeals']);
    Route::get('subcats/{id}', [HomeController::class,'subcats']);

    // Categories, subcategories, and category deals routes
    Route::resource('categories-deal', CategoryDealsController::class);
    Route::resource('categories-bid', CategoryDealsBidController::class);
    Route::resource('categories-reaction', CategoryDealReactionController::class);
    Route::resource('categories-inbox', CategoryInboxController::class);
    // Route::get('categories/{id}/subcategories', [HomeController::class,'subcategories']);
    // Route::get('categories/{id}/subcategories/deals', [HomeController::class,'subcategoryDeals']);


    // Author Profile
    Route::get('user/profile',[UserController::class,'profile']);
    Route::post('user/update',[UserController::class,'updateUser']);
    Route::post('user/delete',[UserController::class,'deleteAccount']);
    Route::post('user/reviews',[UserController::class,'reviews']);
    Route::post('user/send-email-veri-code',[UserController::class,'sendEmailVerifcationCode']);
    Route::post('user/verify-email-veri-code',[UserController::class,'verifyEmailVerifcationCode']);
    Route::post('user/verify-phone',[UserController::class,'verifyPhone']);
    
    // Data
    Route::get('data/create-deal', [DataController::class,'getCreateDealData']);
    
    Route::post('user-deals', [HomeController::class,'userDeals']);
    Route::post('user-cat-deals', [HomeController::class,'userCatDeals']);
    // Rest
    Route::resource('deal', DealController::class);
    Route::get('home-deals', [DealController::class, 'homeDeals']);
    Route::post('deals-history', [DealController::class, 'history']);
    Route::resource('bid', BidController::class);
    Route::resource('inbox', InboxController::class);
    Route::get('inbox-chat/{id}', [InboxController::class.'getChat']);
    Route::post('chattypes', [InboxController::class,'chatType']);
    Route::post('cat-chattypes', [CategoryInboxController::class,'chatType']);
    Route::resource('chat', ChatController::class);
    Route::resource('review', ReviewController::class);
    Route::post('review-history', [ReviewController::class,'history']);
    Route::resource('notification', NotificationController::class);
    Route::resource('reaction', ReactionController::class);
    Route::resource('address', AddressController::class);
    Route::resource('rates', CropRateController::class);
    Route::post('filter-rates', [CropRateController::class, 'getRates']);
    Route::resource('city-rates', CropCityRateController::class);
    Route::post('city-rate-history', [CropCityRateController::class,'cityHistory']);
    Route::post('rates-filter', [CropRateController::class,'filter']);
    Route::get('trending-rates-graph', [CropRateController::class,'trendingCropsGraphs']);
    Route::get('feed-mill-rates', [FeedMillController::class,'index']);
    Route::get('sugar-mill-rates', [SugarMillController::class,'index']);
    
    // Feed
    Route::resource('/feeds', FeedController::class);
    Route::resource('/feeds.likes', FeedLikeController::class);
    Route::resource('/feeds.comments',FeedCommentController::class);

    // Support
    Route::resource('/support', SupportController::class);
    Route::resource('/support.details',SupportDetailController::class);
    
    // Settings
    Route::resource('/settings',SettingController::class);
    
    // Commission Shops routes
    Route::resource('commissionshop', CommissionShopController::class);
    Route::resource('fav-shops', ShopFavoriteController::class);
    Route::resource('reported-shops', ShopReportController::class);
    Route::post('rating', [RatingController::class,'store']);
    Route::post('get-ratings', [RatingController::class,'getRatings']);
    Route::resource('quotes', QuoteController::class);
    Route::get('commissionshop-crops', [CommissionShopController::class,'getShopCropsWithType']);
    Route::post('commissionshop-crops', [CommissionShopController::class,'cropsUpdate']);
    Route::post('commissionshop-post-rates', [CommissionShopController::class,'postCropRate']);
    Route::post('commissionshop-get-nearby', [CommissionShopController::class,'getNearByShop']);
    
    // Videos
    Route::resource('/videos', VideoController::class);
    
    Route::resource('/subscription', SubscriptionController::class);
    Route::resource('/payment-methods', PaymentGatewaysController::class);
    // Ads routes
    Route::resource('/advertisement', AdsController::class);
    // Route::delete('/feeds/{feed}/comments/{comment}', [FeedCommentController::class, 'destroy']);
});

// Route::resource('city-rates', CropCityRateController::class);

// Route::resource('rate', CropRateController::class);

Route::get('/fcm/{id}', function($id){
    $fcmToken = \App\Models\User::find($id)->fcm_token;
    $res =  \App\Helper\FCM::sendNotificationFcm($fcmToken,"Test Title", "Test Body",[ 
        'type' => 'deal', 
        'deal_id' => 1,
    ]);
    return response()->json(['fcm' => $fcmToken, 'res' => $res]);
});

Route::get('userdel', function(){
    return response()->json(['message' => 'Your account deleted successfully']);
});


