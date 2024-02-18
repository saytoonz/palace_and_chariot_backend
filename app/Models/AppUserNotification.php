<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUserNotification extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function appUser(){
        return $this->belongsTo(AppUser::class);
    }

}
