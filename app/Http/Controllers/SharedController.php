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
use App\Models\ApartmentRent;
use App\Models\EventServiceRent;
use App\Models\HotelRent;
use App\Models\Security;
use App\Models\Tourism;
use App\Models\TravelLocations;
use App\Models\VehicleRent;
use App\Models\VehicleSale;

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
}
