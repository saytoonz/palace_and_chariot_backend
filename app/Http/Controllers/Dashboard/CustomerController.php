<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Traits\ApiResponseTrait;

class CustomerController extends Controller
{
    use ApiResponseTrait;

    function getCustomers()
    {
        $appUsers = AppUser::where('is_active', true)->where('is_banned', false)
            ->where('is_deleted', false)->orderBy('created_at','desc')
            ->paginate(1200);

        return $this->ApiResponse(true, 'app_user', null,  $appUsers);
    }

    function searchCustomer($query)
    {
        $appUsers = AppUser::where('is_active', true)->where('is_banned', false)
            ->where('is_deleted', false)
            ->where('first_name', 'LIKE', '%'.$query.'%')
            ->orwhere('last_name', 'LIKE', '%'.$query.'%')
            ->paginate();

        return $this->ApiResponse(true, 'app_user', null,  $appUsers);
    }
}
