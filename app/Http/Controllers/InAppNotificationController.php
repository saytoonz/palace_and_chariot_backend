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
use App\Models\EventServiceRent;
use App\Models\HotelRent;
use App\Models\InAppNotification;
use App\Models\Security;
use App\Models\Tourism;
use App\Models\TravelLocations;
use App\Models\VehicleRent;
use App\Models\VehicleSale;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InAppNotificationController extends Controller
{

    use ApiResponseTrait;

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


}
