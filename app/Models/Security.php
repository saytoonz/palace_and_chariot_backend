<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Security extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function clientType(){
        return $this->hasOne(SecurityClientType::class);
    }

}
