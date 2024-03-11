<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InAppNotification extends Model
{
    use HasFactory;
    protected $guarded = [];



    public function security()
    {
        return  $this->hasOne(Security::class, 'id', 'object_id');
    }

    public function rentVehicle()
    {
        return  $this->hasOne(VehicleRent::class, 'id', 'object_id');
    }

    public function saleVehicle()
    {
        return  $this->hasOne(VehicleSale::class, 'id', 'object_id');
    }

    public function travel()
    {
        return  $this->hasOne(TravelLocations::class, 'id', 'object_id');
    }
    public function tour()
    {
        return  $this->hasOne(Tourism::class, 'id', 'object_id');
    }
    public function saleAccomm()
    {
        return  $this->hasOne(AccommodationSale::class, 'id', 'object_id');
    }
}
