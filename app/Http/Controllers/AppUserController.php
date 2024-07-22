<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppUserResources;
use App\Models\AppUser;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AppUserController extends Controller{
    use ImageTrait;


public function create(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'fuid' => ['required', 'max:255', 'string', 'unique:app_users'],
            'username' => ['min:3', 'max:255', 'string', 'unique:app_users'],
            'email' => ['required', 'max:255', 'email', 'string', 'unique:app_users'],
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
            $avatar = $this->uploadAvatar($request, 'image', 'avatar_'.$appUser->fuid.date('Y-m-d H:i:s'));
            if ($avatar) {
                $appUser->image = $avatar;
                $appUser->save();
            }
        }

        //Create a new notification settins for this user
        $appUser->notiSetting()->create();
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
            'app_user_id' => ['required',  'int'],
            'first_name' => [ 'max:255', 'string'],
            'last_name' => [ 'max:255', 'string'],
            'phone' => ['max:255', 'string'],
        ],
    );

    if ($validator->fails()) {
        return response()->json([
            "error" => true,
            'msg' => $validator->errors()->first(),
        ]);
    }

    //Create a new app user
    $appUser = AppUser::with('notiSetting')->where('id', $request->app_user_id)->where('is_active', true)
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

        if ($request->gender) {
            $usrArray["gender"] = $request->gender;
        }



        if ($request->image) {
            $avatar = $this->uploadAvatar($request, 'image', 'avatar_'.$appUser->fuid.date('Y-m-d H:i:s'));
            if ($avatar) {
                $usrArray["image"] = $avatar;
            }
        }

        $appUser->update($usrArray);

        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => new AppUserResources($appUser->refresh()),
        ]);
    }else{
    return response()->json([
        'error' => true,
        'msg' => "No user found with this credentials",
    ]);
    }


}


public function checkAndLogin(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'fuid' => ['required', 'max:255', 'string'],
            'email' => ['required', 'max:255', 'email', 'string'],
        ],
    );

    if ($validator->fails()) {
        return response()->json([
            "error" => true,
            'msg' => $validator->errors()->first(),
        ]);
    }


    $appUser = AppUser::with('notiSetting')->where('fuid', $request->fuid)->where('email', $request->email)
        ->where('is_active', true)->where('is_banned', false)->where('is_deleted', false)
        ->first();

    if ($appUser) {
        return response()->json([
            "error" => false,
            'msg' => "success",
            'data' => new AppUserResources($appUser),
        ]);
    } else {
        return response()->json([
            "error" => true,
            'msg' => "No user found with this credentials",
        ]);
    }
}

public function deleteMyAccount(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'fuid' => ['required', 'max:255', 'string'],
            'user_id' => ['required', 'int'],
            'email' => ['required', 'max:255', 'email', 'string'],
            'reason' => ['max:255', 'string'],
        ],
    );

    if ($validator->fails()) {
        return response()->json([
            "error" => true,
            'msg' => $validator->errors()->first(),
        ]);
    }


    $appUser = AppUser::with('notiSetting')->where('fuid', $request->fuid)
        ->where('id', $request->user_id)
        ->where('email', $request->email)
        ->where('is_active', true)
        ->where('is_banned', false)
        ->where('is_deleted', false)
        ->first();

    if ($appUser) {

        $userArray = [];
        if ($request->reason) {
            $userArray["delete_reason"] = $request->reason;
        }
        $userArray["fuid"] = 'deleted_'.date('Y-m-d H:i:s').'_'.$appUser->fuid;
        $userArray["email"] = 'deleted_'.date('Y-m-d H:i:s').'_'.$appUser->email;
        $userArray["username"] = 'deleted_'.date('Y-m-d H:i:s').'_'.$appUser->username;
        $userArray["is_deleted"] = true;

        // return $userArray;

        $appUser->update($userArray);

        return response()->json([
            "error" => false,
            'msg' => "Account has been deleted successfully",
        ]);
    } else {
        return response()->json([
            "error" => true,
            'msg' => "No user found with this credentials",
        ]);
    }
}




    //
    // Update push Notification Tokenw
    public function updatePushNotificationToken(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'app_user_id' => ['required',  'int'],
                'noti_token' => ['required'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        $appUser = AppUser::where('id', $request->app_user_id)->where('is_active', true)
                            ->where('is_banned', false)->where('is_deleted', false)->first();

        if ($appUser) {
            if ($request->noti_token) {
                $usrArray["noti_token"] = $request->noti_token;
            }

            $appUser->update($usrArray);

            return response()->json([
                'error' => false,
                'msg' => "success",
                'data' => "token updated successfully"
            ]);
        } else {
            return response()->json([
                'error' => true,
                'msg' => "No user found",
            ]);
        }
    }
}
