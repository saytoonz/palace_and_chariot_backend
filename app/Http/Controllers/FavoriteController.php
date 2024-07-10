<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccommodationSaleResource;
use App\Http\Resources\ApartmentRentResource;
use App\Http\Resources\EventServiceRentResource;
use App\Http\Resources\FavoriteResource;
use App\Http\Resources\HotelRentResource;
use App\Http\Resources\SecurityResource;
use App\Http\Resources\TourismResource;
use App\Http\Resources\TravelLocationResource;
use App\Http\Resources\VehicleRentResource;
use App\Http\Resources\VehicleSaleResource;
use App\Models\AccommodationSale;
use App\Models\ApartmentRent;
use App\Models\EventServiceRent;
use App\Models\Favorite;
use App\Models\HotelRent;
use App\Models\Security;
use App\Models\Tourism;
use App\Models\TravelLocations;
use App\Models\VehicleRent;
use App\Models\VehicleSale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    //
    function toggleFavorite(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'object_id' => ['required', 'int'],
                'app_user_id' => ['required', 'int'],
                'type' => ['required', 'max:255', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        $fav =  Favorite::where('object_id', $request->object_id)
            ->where('app_user_id', $request->app_user_id)
            ->where('type', $request->type)->first();

        if ($fav == null) {
            $create = Favorite::create($request->all());
            if($create){
                 return response()->json([
                "error" => false,
                'msg' => "Item added to favorites successfully",
            ]);
            }else{
                return response()->json([
                    "error" => true,
                    'msg' => "Error adding item to favorites",
                ]);
            }

        } else {
            $delete = Favorite::find($fav->id)->delete();
            if($delete){
                return response()->json([
               "error" => false,
               'msg' => "Item removed from favorites successfully",
           ]);
           }else{
               return response()->json([
                   "error" => true,
                   'msg' => "Error removing item from favorites",
               ]);
           }
        }
    }

    function getUserFavorites($appUserId)
    {
        $data =  FavoriteResource::collection(Favorite::where('type','!=','travel')->where('app_user_id', $appUserId)->orderBy('id', 'desc')->get());
        $data = $data->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        }) ;

        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' =>$data,
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
