<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppUserResources;
use App\Models\AppUser;
// use App\Traits\ApiResponseTrait;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AppUserController extends Controller{
    use ImageTrait;
    // use ApiResponseTrait;


public function create(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'fuid' => ['required', 'max:255', 'string', 'unique:app_users'],
            'first_name' => ['required', 'max:255', 'string'],
            'last_name' => ['required', 'max:255', 'string'],
            'username' => ['required', 'min:3', 'max:255', 'string', 'unique:app_users'],
            'email' => ['required', 'max:255', 'email', 'string', 'unique:app_users'],
            'country' => ['max:255', 'string'],
            'country_code' => ['max:255', 'string'],
            'phone' => ['max:255', 'string'],
            'date_of_birth' => [ 'max:255', 'string'],
            'language' => ['max:255', 'string'],
        ],
    );

    if ($validator->fails()) {
        return response()->json([
            "error" => true,
            'msg' => $validator->errors()->first(),
        ]);
    }

    //Create a new app user
    $appUser = AppUser::create($request->all());
    if ($appUser) {
        if ($request->image) {
            $avatar = $this->uploadAvatar($request, 'image', 'avatar_'.$request->fuid);
            if ($avatar) {
                $appUser->image = $avatar;
                $appUser->save();
            }
        }

    //     //Create a new channel for this user
    //     $appUser->channel()->create([
    //         'name' => $request->username,
    //         'slug' => Str::slug($request->username, '-'),
    //         'uuid' => $uuid,
    //         'description' => $request->bio,
    //         'image' => $appUser->image,
    //     ]);
    }
    return response()->json([
        'error' => false,
        'msg' => "success",
        'data' => new AppUserResources($appUser->refresh()),
    ]);
}

public function update(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'user_id' => ['required',  'int'],
            'fuid' => ['required',  'string'],
            'first_name' => [ 'max:255', 'string'],
            'last_name' => [ 'max:255', 'string'],
            'country' => ['max:255', 'string'],
            'country_code' => ['max:255', 'string'],
            'phone' => ['max:255', 'string'],
            'date_of_birth' => [ 'max:255', 'string'],
            'language' => ['max:255', 'string'],
        ],
    );

    if ($validator->fails()) {
        return response()->json([
            "error" => true,
            'msg' => $validator->errors()->first(),
        ]);
    }

    //Create a new app user
    $appUser = AppUser::where('id', $request->user_id)->where('fuid', $request->fuid)->where('is_active', true)
        ->where('is_banned', false)->where('is_deleted', false)->first();

    if ($appUser) {
        //Updates user
        $usrArray = [];
        if ($request->first_name) {
            $usrArray["first_name"] = $request->first_name;
        }

        if ($request->last_name) {
            $usrArray["last_name"] = $request->last_name;
        }

        if ($request->country) {
            $usrArray["country"] = $request->country;
        }

        if ($request->country_code) {
            $usrArray["country_code"] = $request->country_code;
        }

        if ($request->phone) {
            $usrArray["phone"] = $request->phone;
        }

        if ($request->date_of_birth) {
            $usrArray["date_of_birth"] = $request->date_of_birth;
        }

        if ($request->language) {
            $usrArray["language"] = $request->language;
        }



        if ($request->image) {
            $avatar = $this->uploadAvatar($request, 'image', 'avatar_'.$appUser->fuid);
            if ($avatar) {
                $usrArray["image"] = $avatar;
            }
        }

        $appUser->update($usrArray);
    }

    return response()->json([
        'error' => false,
        'msg' => "success",
        'data' => new AppUserResources($appUser->refresh()),
    ]);
}
}
