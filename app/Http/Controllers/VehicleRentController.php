<?php

namespace App\Http\Controllers;

use App\Models\VehicleRent;
use App\Models\VehicleRentRequest;
use App\Traits\ApiResponseTrait;
use App\Traits\AppUserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleRentController extends Controller
{
    use ApiResponseTrait;
    use AppUserTrait;

    function getRentVehicles(Request $request,  $vehicleType)
    {
        // ->where('vehicle_make_id', $makeId)
        $query =  VehicleRent::with(['make'])->where('type', $vehicleType)
        ->where(function ($query) use ($request) {
                if (isset($request->make_id))   $query->where('vehicle_make_id', $request->make_id);
            });

        if ($request->price_asc == 'true') $query->orderBy('price', 'ASC');
        if ($request->price_desc == 'true') $query->orderBy('price', 'DESC');
        if ($request->distance_away == 'true') $query->orderBy('distance_away', 'ASC');
        if ($request->ratings_asc == 'true') $query->orderBy('ratings_value', 'ASC');
        if ($request->ratings_desc == 'true') $query->orderBy('ratings_value', 'DESC');


        $reuslt =  $query->paginate();
        return $this->ApiResponse(true, 'vehicle_rent', null, $reuslt, true);
    }


    function createVehicleRentRequest(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'vehicle_id' => ['required', 'int'],
                'pickup_location' => ['required', 'max:255', 'string'],
                'pickup_date_time' => ['required', 'max:255', 'string'],
                'dropoff_date_time' => ['required', 'max:255', 'string'],
                'name' => ['required', 'max:255', 'string'],
                'address' => ['required', 'max:255', 'string'],
                'city' => ['required', 'max:255', 'string'],
                'country' => ['required', 'max:255', 'string'],
                'country_code' => ['required', 'max:255', 'string'],
                'phone' => ['required', 'max:255', 'string'],
                'need_our_driver' => ['required', 'boolean'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }
        if (!$request->need_our_driver) {
            $validator = Validator::make(
                $request->all(),
                [
                    'driver_name' => ['required', 'max:255', 'string'],
                    'driver_email' => ['required', 'email', 'string'],
                    'driver_license' => ['required', 'max:255', 'string'],
                    'driver_country' => ['required', 'max:255', 'string'],
                    'driver_country_code' => ['required', 'max:255', 'string'],
                    'driver_phone' => ['required', 'max:255', 'string'],
                    'driver_title' => ['max:255', 'string'],
                ],
            );

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    'msg' => $validator->errors()->first(),
                ]);
            }
        }

        //Create a new Vehicle Rent Request
        $rentRequest = VehicleRentRequest::create($request->all());
        if ($rentRequest) {
            return response()->json([
                'error' => false,
                'msg' => "success",
                'data' => $rentRequest->refresh(),
            ]);
        } else {
            return response()->json([
                'error' => true,
                'msg' => "An error occurred while processing the request",
            ]);
        }
    }
}
