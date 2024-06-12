<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function vehicleKeys()
    {
        return  $this->hasMany(VehicleKeys::class, 'object_id');
    }

    public function keys()
    {
        return $this->vehicleKeys->where('object_type', 'room');
    }
}
