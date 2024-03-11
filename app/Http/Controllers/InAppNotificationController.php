<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccommodationSaleResource;
use App\Http\Resources\InAppNotificationResource;
use App\Http\Resources\SecurityResource;
use App\Http\Resources\TourismResource;
use App\Http\Resources\TravelLocationResource;
use App\Http\Resources\VehicleRentResource;
use App\Http\Resources\VehicleSaleResource;
use App\Models\AccommodationSale;
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
        $data = InAppNotification::where('status', 'active')->get()->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        });

        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => InAppNotificationResource::collection($data),
        ]);
    }



    function getNotificationObject($objectId, $objectType)
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
        }

        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => $returnData,
        ]);
    }
}
