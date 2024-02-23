<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function clientType(){
        return $this->belongsTo(SecurityClientType::class, 'security_client_type_id');
    }

}
