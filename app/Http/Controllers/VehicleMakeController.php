<?php

namespace App\Http\Controllers;

use App\Models\VehicleMake;
use Illuminate\Http\Request;

class VehicleMakeController extends Controller
{
    function getRentMakes($vehicleType) {
        return VehicleMake::where('vehicle_type',$vehicleType)->where('status','active')->get();
    }
}
