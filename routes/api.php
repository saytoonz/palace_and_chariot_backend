<?php

use App\Http\Controllers\AccommodationSaleController;
use App\Http\Controllers\ApartmentRentController;
use App\Http\Controllers\AppUserController;
use App\Http\Controllers\AppUserNotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EventServiceRentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\InAppNotificationController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\TourismController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\VehicleMakeController;
use App\Http\Controllers\VehicleRentController;
use App\Http\Controllers\VehicleSaleController;
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

    ///Vehicles
    //- Rentals
    Route::get('get-vehicle-makes/{vehicleType}', [VehicleMakeController::class, 'getRentMakes']);
    Route::get('get-rent-vehicles/{vehicleType}', [VehicleRentController::class, 'getRentVehicles']);
    Route::post('create-vehicle-rent-request', [VehicleRentController::class, 'createVehicleRentRequest']);
    //- Sale
    Route::get('get-sale-vehicles', [VehicleSaleController::class, 'getSaleVehicles']);
    Route::post('create-vehicle-sale-request', [VehicleSaleController::class, 'createVehicleSaleRequest']);

   ///Accommodations
    //- Sale
    Route::get('get-sale-accomms', [AccommodationSaleController::class, 'getSaleAccomms']);
    Route::post('create-accomm-sale-request', [AccommodationSaleController::class, 'createCallBackRequest']);

    //Event Services
    Route::get('get-rent-event-services', [EventServiceRentController::class, 'getEventServices']);
    Route::post('create-event-rent-request', [EventServiceRentController::class, 'createRequest']);


    //Rent Apartment Services
    Route::get('get-rent-apartments', [ApartmentRentController::class, 'getApartmentServices']);
    Route::post('create-apartment-rent-request', [ApartmentRentController::class, 'createRequest']);




    //
    Route::post('toggle-favorite', [FavoriteController::class, 'toggleFavorite']);

    //Security
    Route::get('get-all-securities', [SecurityController::class, 'getAllSecurities']);
    Route::get('get-security-client-type', [SecurityController::class, 'getSecurityClientType']);
    Route::post('create-security-request', [SecurityController::class, 'createSecurityRequest']);

    //Travel
    Route::get('get-travel-locations', [TravelController::class, 'getLocations']);
    Route::post('create-travel-request', [TravelController::class, 'createtravelRequest']);

    //Tourism
    Route::get('get-tourisms-sites', [TourismController::class, 'getSites']);
    Route::post('create-tour-request', [TourismController::class, 'createTourRequest']);



    //Chat
    Route::post('send-chat-message', [ChatController::class, 'sendMessage']);
    Route::get('get-chat-list/{app_user_id}', [ChatController::class, 'getChatList']);
    Route::get('get-chat-messages/{appu_ser_id}/{object_id}/{object_type}/{quantity}',[ ChatController::class, 'getChats']);
    Route::get('get-new-chat-messages/{appu_ser_id}/{object_id}/{object_type}/{last_msg_id}',[ ChatController::class, 'getNewChats']);

    //In app notificacion
    Route::get('in-app-notifications/{app_user_id}', [InAppNotificationController::class, 'getUserInAppNorifications']);
    Route::get('get-notification-object/{object_id}/{object_type}', [InAppNotificationController::class, 'getNotificationObject']);

});

