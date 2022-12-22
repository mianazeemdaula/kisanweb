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

Route::post('auth/login', [AuthController::class,'login']);
Route::post('auth/signup', [AuthController::class,'signup']);
Route::post('auth/mobileRegister', [AuthController::class,'mobileRegister']);
Route::post('auth/loginsocial', [AuthController::class,'loginFromSocial']);
Route::post('auth/{provider}/callback', [AuthController::class,'socialcallback']);

Route::resource('crop', CropController::class);
Route::resource('crop.type', CropTypeController::class);

Route::resource('media', MediaController::class);

Route::get('crops', [HomeController::class,'crops']);
Route::get('popular', [HomeController::class,'popular']);
Route::get('latest', [HomeController::class,'latest']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Author Profile
    Route::get('user/profile',[UserController::class,'profile']);
    Route::post('user/update',[UserController::class,'updateUser']);
    // Rest
    Route::resource('deal', DealController::class);
    Route::resource('bid', BidController::class);
});
