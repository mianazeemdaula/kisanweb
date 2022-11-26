<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\CropController;
use App\Http\Controllers\Api\V1\CropTypeController;
use App\Http\Controllers\Api\V1\OfferController;
use App\Http\Controllers\Api\V1\AuthController;
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

Route::post('/login', [AuthController::class,'login']);

Route::resource('crop', CropController::class);
Route::resource('crop.type', CropTypeController::class);
Route::resource('crop.type.offer', OfferController::class);
