<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelLocations extends Model
{
    use HasFactory;
    protected $guarded = [];



    public function favorites()
    {
        return  $this->hasMany(Favorite::class, 'object_id');
    }

    public function isFavorite($userId)
    {
        return (bool) $this->favorites->where('type', 'travel')->where('app_user_id', $userId)->first();
    }




    public function allImages()
    {
        return  $this->hasMany(Image::class, 'object_id');
    }

    public function gallery()
    {
        return $this->allImages->where('object_type', 'travel');
    }

}
