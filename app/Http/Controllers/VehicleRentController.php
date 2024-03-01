<?php

namespace App\Http\Controllers;

use App\Models\VehicleRent;
use App\Traits\ApiResponseTrait;

class VehicleRentController extends Controller
{
    use ApiResponseTrait;
    //
    function getRentVehicles($vehicleType, $makeId) {

        $data =  VehicleRent::with(['make'])->where('vehicle_make_id',$makeId)->where('type',$vehicleType)->paginate();
        return $this->ApiResponse(true, 'vehicle_rent',null,$data, true);
    }
}
