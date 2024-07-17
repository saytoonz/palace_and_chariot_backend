<?php

namespace App\Http\Controllers;

use App\Http\Resources\VehicleMakeResource;
use App\Models\VehicleMake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleMakeController extends Controller
{
    function getRentMakes($vehicleType)
    {
        $data = VehicleMake::where(function ($query) use ($vehicleType) {
            $values  =  explode('_', $vehicleType);
            $query->where('vehicle_type',  'LIKE', '%' . $values[0] . '%');
            if (count($values) > 1) {
                $query->orwhere('vehicle_type',  'LIKE', '%' . $values[1] . '%');
            }
        })->where('image', '!=', null)->where('status', 'active')->get();

        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => VehicleMakeResource::collection($data),
        ]);
    }

    function getAllMakes()
    {
        $data = VehicleMake::where('image', '!=', null)->where('status', 'active')->get();
        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => VehicleMakeResource::collection($data),
        ]);
    }

    function addVehicleMake(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'logged_in_user_id' => ['required', 'int'],
                'name' => ['required', 'string'],
                'image' => ['required', 'string'],
            ],
        );


        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        $make =  VehicleMake::where('name', $request->name)->where('status', 'active')->get();
        if (count($make) > 0) {
            return response()->json([
                "error" => true,
                'msg' =>  "A make already exists with this name.",
            ]);
        }

        $newMake =  VehicleMake::create([
            'name' => $request->name,
            'image' => trim($request->image),
            'vehicle_type' => $request->vehicle_type,
        ],);

        if ($newMake) {
            $data = VehicleMake::where('image', '!=', null)->where('status', 'active')->get();
            return response()->json([
                'error' => false,
                'msg' => "success",
                'data' => VehicleMakeResource::collection($data),
            ]);
        } else {
            return response()->json([
                "error" => true,
                'msg' => "An error occurred",
            ]);
        }
    }
}
