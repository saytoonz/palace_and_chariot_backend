<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccommodationSaleResource;
use App\Http\Resources\ApartmentRentResource;
use App\Http\Resources\EventServiceRentResource;
use App\Http\Resources\HotelRentResource;
use App\Http\Resources\InAppNotificationResource;
use App\Http\Resources\SecurityResource;
use App\Http\Resources\TourismResource;
use App\Http\Resources\TravelLocationResource;
use App\Http\Resources\VehicleRentResource;
use App\Http\Resources\VehicleSaleResource;
use App\Models\AccommodationSale;
use App\Models\ApartmentRent;
use App\Models\AppUser;
use App\Models\EventServiceRent;
use App\Models\HotelRent;
use App\Models\InAppNotification;
use App\Models\Security;
use App\Models\Tourism;
use App\Models\TravelLocations;
use App\Models\VehicleRent;
use App\Models\VehicleSale;
use App\Traits\ApiResponseTrait;
use App\Traits\NotificationsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InAppNotificationController extends Controller
{

    use ApiResponseTrait;
    use NotificationsTrait;

    function getUserInAppNorifications($appUserId)
    {
        $data = InAppNotification::where('status', 'active')->orderBy('id', 'desc')->get()->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        });

        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => InAppNotificationResource::collection($data),
        ]);
    }



    function createInApp($title, $body, $objectId, $objectType, $image, $topic = '')
    {
        $created = InAppNotification::create([
            'title' => $title,
            'body' => $body,
            'object_id' => $objectId,
            'object_type' => $objectType,
            'image' => $image,
            'app_users' => '0'
        ]);

        if ($topic != '')  $this->sendTopicPushNotification($topic, $title, $body);
        return $created;
    }
}
