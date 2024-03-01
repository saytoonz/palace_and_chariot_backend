<?php

use App\Http\Controllers\AppUserController;
use App\Http\Controllers\AppUserNotificationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\VehicleMakeController;
use App\Http\Controllers\VehicleRentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Intervention\Image\Laravel\Facades\Image;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('app')->group(function (){
    Route::post('create-user', [AppUserController::class, 'create']);
    Route::post('update-user', [AppUserController::class, 'update']);
    Route::post('check-and-login-user', [AppUserController::class, 'checkAndLogin']);
    Route::post('delete-account', [AppUserController::class, 'deleteMyAccount']);


    Route::post('update-user-notification', [AppUserNotificationController::class, 'update']);


    Route::get('get-vehicle-makes/{vehicleType}', [VehicleMakeController::class, 'getRentMakes']);
    Route::get('get-rent-vehicles/{vehicleType}/{makeId}/', [VehicleRentController::class, 'getRentVehicles']);

    //
    Route::post('toggle-favorite', [FavoriteController::class, 'toggleFavorite']);

    //Security
    Route::get('get-all-securities', [SecurityController::class, 'getAllSecurities']);
    Route::get('get-security-client-type', [SecurityController::class, 'getSecurityClientType']);
    Route::post('create-security-request', [SecurityController::class, 'createSecurityRequest']);


});

