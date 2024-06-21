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
use App\Models\AppUser;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        $all = collect()->merge($apartments)->merge($hotel)->merge($events)->merge($security)
            ->merge($tourism)->merge($travel)->merge($rentVehicle);

        return  response()->json([
            'error' => false,
            'msg' => "success",
            'data' => $all->sortBy('created_at')->values(),
        ]);
    }

    function completedRequests($appUserId) {
        $apartments = ApartmentRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'close')->get();
        $hotel = HotelRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'close')->get();
        $events = EventRentRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'close')->get();
        $security = SecurityRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'close')->get();
        $tourism = TourismRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'close')->get();
        $travel = TravelRequest::with(['departure','droppOff'])->where('app_user_id', $appUserId)->where('status', 'close')->orwhere('status', 'close')->get();
        $rentVehicle = VehicleRentRequest::where('app_user_id', $appUserId)->where('status', 'completed')->orwhere('status', 'close')->get();


        $all = collect()->merge($apartments)->merge($hotel)->merge($events)->merge($security)
            ->merge($tourism)->merge($travel)->merge($rentVehicle);

        return  response()->json([
            'error' => false,
            'msg' => "success",
            'data' => $all->sortBy('created_at')->values(),
        ]);
    }

    function cancelRequests(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'object_id' => ['required', 'max:255', 'int'],
                'object_type' => ['required', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }


        $objectType = $request->object_type;
        $objectId = $request->object_id;

        $data = null;
        if ($objectType == 'security') {
            $data = SecurityRequest::find($objectId);
        } else if ($objectType == 'rent_vehicle') {
            $data = VehicleRentRequest::find($objectId);
        } else if ($objectType == 'rent_hotel') {
            $data = HotelRequest::find($objectId);
        } else if ($objectType == 'rent_apartment') {
            $data = ApartmentRequest::find($objectId);
        } else if ($objectType == 'sale_vehicle') {
            $data = VehicleSaleRequest::find($objectId);
        } else if ($objectType == 'travel') {
            $data = TravelRequest::find($objectId);
        } else if ($objectType == 'tour') {
            $data = TourismRequest::find($objectId);
        } else if ($objectType == 'sale_accomm') {
            $data = AccommodationSaleRequest::find($objectId);
        } else if ($objectType == 'rent_event') {
            $data = EventRentRequest::find($objectId);
        }

        if ($data) {
            if( $data->status == 'pending' || $data->status == 'active' ) {
                $data->status = 'close';
                $data->save();
            }

            return response()->json([
                'error' => false,
                'msg' => "Update successful",
            ]);
        }else{
            return response()->json([
                'error' => true,
                'msg' => "An error occurred, item not found!",
            ]);
        }
    }

}
