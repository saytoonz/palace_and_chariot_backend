<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function notiSetting(){
        return $this->hasOne(AppUserNotification::class);
    }
}
