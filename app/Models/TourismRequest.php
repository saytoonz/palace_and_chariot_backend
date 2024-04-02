<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismRequest extends Model
{
    use HasFactory;
    protected $guarded =[];


    public function tour()
    {
        return  $this->hasOne(Tourism::class, 'id', 'tour_site_id');
    }
}
