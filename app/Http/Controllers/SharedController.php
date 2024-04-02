<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccommodationSaleResource;
use App\Http\Resources\ApartmentRentResource;
use App\Http\Resources\EventServiceRentResource;
use App\Http\Resources\HotelRentResource;
use App\Http\Resources\SecurityResource;
use App\Http\Resources\TourismResource;
use App\Http\Resources\TravelLocationResource;
use App\Http\Resources\VehicleRentResource;
use App\Http\Resources\VehicleSaleResource;
use App\Models\AccommodationSale;
use App\Models\AccommodationSaleRequest;
use App\Models\ApartmentRent;
use App\Models\ApartmentRequest;
use App\Models\EventRentRequest;
use App\Models\EventServiceRent;
use App\Models\HotelRent;
use App\Models\HotelRequest;
use App\Models\Security;
use App\Models\SecurityRequest;
use App\Models\Tourism;
use App\Models\TourismRequest;
use App\Models\TravelLocations;
use App\Models\TravelRequest;
use App\Models\VehicleRent;
use App\Models\VehicleRentRequest;
use App\Models\VehicleSale;
use App\Models\VehicleSaleRequest;
use Illuminate\Support\Facades\DB;

class SharedController extends Controller
{
    //
    function getObject($objectId, $objectType)
    {
        $returnData = null;

        if ($objectType == 'security') {
            $data = Security::find($objectId);
            if ($data) {
                $returnData = new SecurityResource($data);
            }
        } else if ($objectType == 'rent_vehicle') {
            $data = VehicleRent::find($objectId);
            if ($data) {
                $returnData = new VehicleRentResource($data);
            }
        }  else if ($objectType == 'rent_hotel') {
            $data = HotelRent::find($objectId);
            if ($data) {
                $returnData = new HotelRentResource($data);
            }
        }  else if ($objectType == 'rent_apartment') {
            $data = ApartmentRent::find($objectId);
            if ($data) {
                $returnData = new ApartmentRentResource($data);
            }
        } else if ($objectType == 'sale_vehicle') {
            $data = VehicleSale::find($objectId);
            if ($data) {
                $returnData = new VehicleSaleResource($data);
            }
        } else if ($objectType == 'travel') {
            $data = TravelLocations::find($objectId);
            if ($data) {
                $returnData = new TravelLocationResource($data);
            }
        } else if ($objectType == 'tour') {
            $data = Tourism::find($objectId);
            if ($data) {
                $returnData = new TourismResource($data);
            }
        } else if ($objectType == 'sale_accomm') {
            $data = AccommodationSale::find($objectId);
            if ($data) {
                $returnData = new AccommodationSaleResource($data);
            }
        } else if ($objectType == 'rent_event') {
            $data = EventServiceRent::find($objectId);
            if ($data) {
                $returnData = new EventServiceRentResource($data);
            }
        }

        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => $returnData,
        ]);
    }


    function ongoingRequests($appUserId) {

        $apartments = ApartmentRequest::where('app_user_id', $appUserId)->where('status', 'pending')->orwhere('status', 'active')->get();
        $hotel = HotelRequest::where('app_user_id', $appUserId)->where('status', 'pending')->orwhere('status', 'active')->get();
        $events = EventRentRequest::where('app_user_id', $appUserId)->where('status', 'pending')->orwhere('status', 'active')->get();
        $security = SecurityRequest::where('app_user_id', $appUserId)->where('status', 'pending')->orwhere('status', 'active')->get();
        $tourism = TourismRequest::where('app_user_id', $appUserId)->where('status', 'pending')->orwhere('status', 'active')->get();
        $travel = TravelRequest::with(['departure','droppOff'])->where('app_user_id', $appUserId)->where('status', 'pending')->orwhere('status', 'active')->get();
        $rentVehicle = VehicleRentRequest::where('app_user_id', $appUserId)->where('status', 'pending')->orwhere('status', 'active')->get();

        $data = [
            ...$apartments,
            ...$hotel,
            ...$events,
            ...$security,
            ...$tourism,
            ...$travel,
            ...$rentVehicle,
        ];

        return  response()->json([
            'error' => false,
            'msg' => "success",
            'data' => $data,
        ]);
    }

    function completedRequests($appUserId) {
        $apartments = ApartmentRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'cancelled')->get();
        $hotel = HotelRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'cancelled')->get();
        $events = EventRentRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'cancelled')->get();
        $security = SecurityRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'cancelled')->get();
        $tourism = TourismRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'cancelled')->get();
        $travel = TravelRequest::with(['departure','droppOff'])->where('app_user_id', $appUserId)->where('status', 'cancelled')->orwhere('status', 'cancelled')->get();
        $rentVehicle = VehicleRentRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'cancelled')->get();

        $data = [
            ...$apartments,
            ...$hotel,
            ...$events,
            ...$security,
            ...$tourism,
            ...$travel,
            ...$rentVehicle,
        ];

        return  response()->json([
            'error' => false,
            'msg' => "success",
            'data' => $data,
        ]);
    }
}
