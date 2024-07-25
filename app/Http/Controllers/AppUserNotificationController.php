<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppUserResources;
use App\Models\AppUser;
use App\Traits\NotificationsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppUserNotificationController extends Controller
{

    use NotificationsTrait;

    //
    public function update(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'app_user_id' => ['required',  'int'],
            'chat_box' => ['bool'],
            'travel_ideas' => ['bool'],
            'rentals' =>['bool'],
            'security' =>['bool'],
            'sales' =>['bool'],
            'upcoming_deals' => ['bool'],
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

        $notiArray = [];

        if (isset($request->chat_box)) {
            $notiArray["chat_box"] = $request->chat_box;
        }

        if (isset($request->travel_ideas)) {
            $notiArray["travel_ideas"] = $request->travel_ideas;
        }

        if (isset($request->rentals)) {
            $notiArray["rentals"] = $request->rentals;
        }

        if (isset($request->security)) {
            $notiArray["security"] = $request->security;
        }

        if (isset($request->sales)) {
            $notiArray["sales"] = $request->sales;
        }

        if (isset($request->upcoming_deals)) {
            $notiArray["upcoming_deals"] = $request->upcoming_deals;
        }


        $appUser->notiSetting()->update($notiArray);

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

    function sendNotification()  {
        return $this->sendTokenPushNotification(
          "cLObSIS4SwKytWuRTNiZBf:APA91bHeCPanOibmOUtq0it1UZYozXx41LQhdybj_3cCu_IG2y6FWoi-2hE_zuN4lZ3AF-c1EcCzZT45U385S4L-bhAgl0PBowwntpbuV2oGH-MVmpsT2NnMhkJDa7Fbk4r9vMky-znQ",
          'Just title',
          'Body goes in here',
        );
    }
}
