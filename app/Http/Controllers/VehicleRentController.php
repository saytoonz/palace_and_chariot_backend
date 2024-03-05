<?php

namespace App\Http\Controllers;

use App\Models\VehicleRent;
use App\Traits\ApiResponseTrait;
use App\Traits\AppUserTrait;
use Illuminate\Http\Request;

class VehicleRentController extends Controller
{
    use ApiResponseTrait;
    use AppUserTrait;

    function getRentVehicles($vehicleType, $makeId)
    {
        $data =  VehicleRent::with(['make'])->where('vehicle_make_id', $makeId)->where('type', $vehicleType)->paginate();
        return $this->ApiResponse(true, 'vehicle_rent', null, $data, true);
    }
}
