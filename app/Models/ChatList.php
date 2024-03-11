<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatList extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function saleVehicle()
    {
        return  $this->hasOne(VehicleSale::class, 'id', 'object_id');
    }

    public function saleAccomm()
    {
        return  $this->hasOne(AccommodationSale::class, 'id', 'object_id');
    }

}
