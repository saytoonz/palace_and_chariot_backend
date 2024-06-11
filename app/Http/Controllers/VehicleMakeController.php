<?php

namespace App\Http\Controllers;

use App\Http\Resources\VehicleMakeResource;
use App\Models\VehicleMake;
use Illuminate\Http\Request;

class VehicleMakeController extends Controller
{
    function getRentMakes($vehicleType) {
        $data = VehicleMake::where(function($query) use ($vehicleType) {
            $values  =  explode('_',$vehicleType);
            $query->where('vehicle_type',  'LIKE', '%'.$values[0].'%');
            if(count($values) > 1){
                $query->orwhere('vehicle_type',  'LIKE', '%'.$values[1].'%');
            }
        })->where('image', '!=', null)->where('status','active')->get();

        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => VehicleMakeResource::collection($data),
        ]);
    }

    function getAllMakes() {
        $data = VehicleMake::where('image', '!=', null)->where('status','active')->get();
        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => VehicleMakeResource::collection($data),
        ]);
    }
}
