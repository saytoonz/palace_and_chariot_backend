<?php

namespace App\Http\Controllers;

use App\Models\VehicleSale;
use App\Models\VehicleSaleRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleSaleController extends Controller
{
    use ApiResponseTrait;


    function getSaleVehicles(Request $request)
    {
        $data =  VehicleSale::with(['make']) ->where(function ($query) use ($request) {
            if (isset($request->make_id))   $query->where('vehicle_make_id', $request->make_id);
        })->paginate();
        return $this->ApiResponse(true, 'vehicle_sale', null, $data, true);
    }


    function createVehicleSaleRequest(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'vehicle_id' => ['required', 'int'],
                'name' => ['required', 'max:255', 'string'],
                'email' => ['required', 'email', 'string'],
                'country_code' => ['required', 'max:255', 'string'],
                'phone' => ['required', 'max:255', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }


        //Create a new Vehicle Sale Request
        $rentRequest = VehicleSaleRequest::create($request->all());
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
