<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationSale extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function favorites()
    {
        return  $this->hasMany(Favorite::class, 'object_id');
    }

    public function isFavorite($userId)
    {
        return (bool) $this->favorites->where('type', 'sale_accomm')->where('app_user_id', $userId)->first();
    }



    public function allImages()
    {
        return  $this->hasMany(Image::class, 'object_id');
    }

    public function gallery()
    {
        return $this->allImages->where('object_type', 'sale_accomm');
    }



    public function vehicleKeys()
    {
        return  $this->hasMany(VehicleKeys::class, 'object_id');
    }

    public function keys()
    {
        return $this->vehicleKeys->where('object_type', 'sale_accomm');
    }




    public function vehicleTextKeys()
    {
        return  $this->hasMany(VehicleTextKey::class, 'object_id');
    }

    public function textKeys()
    {
        return $this->vehicleTextKeys->where('object_type', 'sale_accomm');
    }

}
