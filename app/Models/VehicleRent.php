<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleRent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function make(){
        return $this->belongsTo(VehicleMake::class, 'vehicle_make_id');
    }
}
