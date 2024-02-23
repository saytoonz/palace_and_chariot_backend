<?php
namespace App\Traits;
use App\Models\AppUser;

trait AppUserTrait {

    public function getAppUserWithUserId( $userId)
    {
        $appUser = AppUser::where('id', $userId)->where('is_active', true)
        ->where('is_banned', false)->where('is_deleted', false)->first();

        return $appUser;
    }

    public function getAppUserWithFuid($fuid)
    {
        $appUser = AppUser::where('fuid', $fuid)->where('is_active', true)
        ->where('is_banned', false)->where('is_deleted', false)->first();

        return $appUser;
    }


    public function getAppUserWithUsername($username)
    {
        $appUser = AppUser::where('username', $username)->where('is_active', true)
        ->where('is_banned', false)->where('is_deleted', false)->first();

        return $appUser;
    }


    public function getAppUserWithIdAndFuid($userId, $fuid)
    {
        $appUser = AppUser::where('id', $userId)->where('fuid', $fuid)->where('is_active', true)
        ->where('is_banned', false)->where('is_deleted', false)->first();

        return $appUser;
    }
}
