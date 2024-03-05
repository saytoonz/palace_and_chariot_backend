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

    public function favorites(){
        return  $this->hasMany(Favorite::class, 'object_id');
    }


    public function isFavorite($userId){
        return (bool) $this->favorites->where('type','rent_vehicle')->where('app_user_id', $userId)->first();
     }

    public function allImages(){
        return  $this->hasMany(Image::class, 'object_id');
    }

    public function gallery(){
        return $this->allImages->where('object_type','vehicle_rent');
     }

     public function vehicleKeys(){
        return  $this->hasMany(VehicleKeys::class, 'object_id');
    }

    public function keys(){
        return $this->vehicleKeys->where('object_type','vehicle_rent');
     }


}
