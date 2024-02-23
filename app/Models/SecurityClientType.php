<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityClientType extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function security(){
        return $this->belongsTo(Security::class);
    }

    public function securityRequest(){
        return $this->hasMany(SecurityRequest::class);
    }

}
