<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function departure()
    {
        return  $this->hasOne(TravelLocations::class, 'id', 'depart_location_id');
    }

    public function droppOff()
    {
        return  $this->hasOne(TravelLocations::class, 'id' ,'return_location_id');
    }
}
