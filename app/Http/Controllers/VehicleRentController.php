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

    function getRentVehicles($vehicleType, $makeId)
    {
        $data =  VehicleRent::with(['make'])->where('vehicle_make_id', $makeId)->where('type', $vehicleType)->paginate();
        return $this->ApiResponse(true, 'vehicle_rent', null, $data, true);
    }


    function createVehicleRentRequest(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
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
        if (!$request->needOurDriver) {
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
