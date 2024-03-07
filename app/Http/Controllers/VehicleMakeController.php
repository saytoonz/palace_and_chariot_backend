<?php

namespace App\Http\Controllers;

use App\Http\Resources\VehicleMakeResource;
use App\Models\VehicleMake;
use Illuminate\Http\Request;

class VehicleMakeController extends Controller
{
    function getRentMakes($vehicleType) {
        $data = VehicleMake::where('vehicle_type',$vehicleType)->where('image', '!=', null)->where('status','active')->get();
        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => VehicleMakeResource::collection($data),
        ]);
    }
}
