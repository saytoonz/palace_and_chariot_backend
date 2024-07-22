<?php

use App\Http\Controllers\Dashboard\AccessLogController;
use App\Http\Controllers\AccommodationSaleController;
use App\Http\Controllers\Dashboard\ActivityLogController;
use App\Http\Controllers\ApartmentRentController;
use App\Http\Controllers\AppUserController;
use App\Http\Controllers\AppUserNotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DashboardUserController;
use App\Http\Controllers\Dashboard\MessagesController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\EventServiceRentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HotelRentController;
use App\Http\Controllers\InAppNotificationController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\SharedController;
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


    Route::post('push-notification-token', [AppUserController::class, 'updatePushNotificationToken']);
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

     //Rent Hotel Services
     Route::get('get-rent-hotels', [HotelRentController::class, 'getHotelsServices']);
     Route::post('create-hotel-rent-request', [HotelRentController::class, 'createRequest']);




    //
    Route::post('toggle-favorite', [FavoriteController::class, 'toggleFavorite']);
    Route::get('get-user-favorites/{appUserId}', [FavoriteController::class, 'getUserFavorites']);

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


    //SHARED CONTROLLER
    //Requests
    Route::get('ongoing-requests/{app_user_id}', [SharedController::class,'ongoingRequests']);
    Route::get('completed-requests/{app_user_id}', [SharedController::class,'completedRequests']);
    Route::post('cancel-requests', [SharedController::class,'cancelRequests']);
    //Object
    Route::get('get-object/{object_id}/{object_type}', [SharedController::class, 'getObject']);

});




Route::prefix('dashboard')->group(function (){
    Route::post('create-user', [DashboardUserController::class, 'create']);
    Route::post('get-dashboard-user' , [DashboardUserController::class, 'getDashboardUser']);
    Route::post('update-user', [DashboardUserController::class, 'update']);
    Route::post('check-and-login-user', [DashboardUserController::class, 'checkAndLogin']);
    Route::post('forgot-password', [DashboardUserController::class, 'forgotPassword']);
    Route::post('reset-password', [DashboardUserController::class, 'resetPassword']);
    Route::post('update-password', [DashboardUserController::class, 'updatePassword']);

    Route::get('get-statistics', [DashboardController::class, 'getStatistics']);
    Route::get('get-active-request', [DashboardController::class, 'getActiveRequest']);
    Route::get('get-pending-request', [DashboardController::class, 'getPendingRequest']);
    Route::get('get-closed-request', [DashboardController::class, 'getClosedRequest']);
    Route::post('update-request-status', [DashboardController::class, 'updateRequestStatus']);
    Route::post('update-request-opened_by', [DashboardController::class, 'updateRequestOpenedBy']);

    Route::get('get-customers', [CustomerController::class, 'getCustomers']);
    Route::get('search-customers/{query}', [CustomerController::class, 'searchCustomer']);

    Route::get('get-access-logs', [AccessLogController::class, 'getAccessLogs']);
    Route::get('get-activity-logs', [ActivityLogController::class, 'getActivityLogs']);

    Route::get('admin-get-active-users/{admin_id}', [DashboardUserController::class, 'adminGetActiveUsers']);
    Route::post('admin-update-user/{admin_id}', [DashboardUserController::class, 'adminUpdateUser']);
    Route::get('admin-get-requested-users/{admin_id}', [DashboardUserController::class, 'adminGetRequestedUsers']);
    Route::post('admin-accept-user/{admin_id}', [DashboardUserController::class, 'adminAcceptUser']);
    Route::post('admin-reject-user/{admin_id}', [DashboardUserController::class, 'adminRejectUser']);

    Route::post('send-chat-message', [MessagesController::class, 'sendMessage']);
    Route::get('get-chat-lists', [MessagesController::class, 'getChatList']);
    Route::get('get-chat-messages/{appu_ser_id}/{object_id}/{object_type}/{quantity}',[ MessagesController::class, 'getChats']);
    Route::get('get-new-chat-messages/{appu_ser_id}/{object_id}/{object_type}/{last_msg_id}',[ MessagesController::class, 'getNewChats']);

    Route::post('upload-product-image', [ProductController::class, 'uploadProductImge']);
    Route::get('get-products', [ProductController::class, 'getProducts']);
    Route::post('create-tourism-product', [ProductController::class, 'createTourismProduct']);
    Route::post('create-security-product', [ProductController::class, 'createSecurityProduct']);
    Route::post('create-sale-vehicle-product', [ProductController::class, 'createSaleVehicleProduct']);
    Route::post('create-sale-accommodation-product', [ProductController::class, 'createSaleAccommodationProduct']);
    Route::post('create-rent-vehicle-product', [ProductController::class, 'createRentVehicleProduct']);
    Route::post('create-rent-accommodation-product', [ProductController::class, 'createRentAccommodationProduct']);
    Route::post('create-rent-event-service', [ProductController::class, 'createRentEventServiceProduct']);

    Route::post('update-tourism-product', [ProductController::class, 'updateTourismProduct']);
    Route::post('update-security-product', [ProductController::class, 'updateSecurityProduct']);
    Route::post('update-sale-vehicle-product', [ProductController::class, 'updateSaleVehicleProduct']);
    Route::post('update-sale-accommodation-product', [ProductController::class, 'updateSaleAccommodationProduct']);
    Route::post('update-rent-vehicle-product', [ProductController::class, 'updateRentVehicleProduct']);
    Route::post('update-rent-accommodation-product', [ProductController::class, 'updateRentAccommodationProduct']);
    Route::post('update-rent-event-service', [ProductController::class, 'updateRentEventServiceProduct']);

    Route::get('get-all-vehicle-makes', [VehicleMakeController::class, 'getAllMakes']);
    Route::post('add-vehicle-make', [VehicleMakeController::class, 'addVehicleMake']);

    Route::post('push-notification-token', [DashboardUserController::class, 'updatePushNotificationToken']);
});

