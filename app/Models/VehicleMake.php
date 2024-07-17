<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function rents(){
        return $this->hasMany(VehicleRent::class);
    }
}
