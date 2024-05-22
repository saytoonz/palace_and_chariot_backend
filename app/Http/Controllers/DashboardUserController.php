<?php

namespace App\Http\Controllers;

use App\Models\DashboardUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardUserController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'max:255', 'email', 'string', 'unique:dashboard_users'],
                'password' => ['required', 'min:6', 'max:255', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }
        //Create a new dashboard user
        $dashUser = DashboardUser::create([
            'email' => $request->email,
            'password' => md5(sha1($request->password)),
        ]);

        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => $dashUser->refresh(),
        ]);
    }


    public function checkAndLogin(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password' => ['required', 'max:255', 'string'],
                'email' => ['required', 'max:255', 'email', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }


        $dashUser = DashboardUser::where('password', md5(sha1($request->password)))->where('email', $request->email)
            ->where('status', 'active')->where('is_deleted', false)
            ->first();

        if ($dashUser) {
            return response()->json([
                "error" => false,
                'msg' => "success",
                'data' => $dashUser,
            ]);
        } else {
            return response()->json([
                "error" => true,
                'msg' => "No active user found with this credentials",
            ]);
        }
    }

    public function  forgotPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'max:255', 'email', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        $dashUser = DashboardUser::where('email', $request->email)->where('status', 'active')->where('is_deleted', false)
            ->first();

        if ($dashUser) {
            $fogetPassCode  =   rand(1000, 9999);
            $dashUser->rest_pass_code = $fogetPassCode;
            $dashUser->save();

            return response()->json([
                "error" => false,
                'msg' => "success",
                'data' => 'Email has been sent to ' . $request->email . ' with pasword update code (Code: ' . $fogetPassCode . ')',
            ]);
        } else {
            return response()->json([
                "error" => true,
                'msg' => "No active user found with this credentials",
            ]);
        }
    }


    public function  updatePassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'max:255', 'email', 'string'],
                'code' => ['required', 'max:4', 'string'],
                'new_password' => ['required', 'min:6', 'max:255', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        $dashUser = DashboardUser::where('email', $request->email)->where('status', 'active')->where('is_deleted', false)
            ->first();

        if ($dashUser) {
            if ($dashUser->rest_pass_code == $request->code) {
                $dashUser->password = md5(sha1($request->new_password));
                $dashUser->rest_pass_code = null;
                $dashUser->save();

                return response()->json([
                    "error" => false,
                    'msg' => "success",
                    'data' => 'Password changed successfully',
                ]);
            } else {
                return response()->json([
                    "error" => true,
                    'msg' => "Sorry, you entered incorrect code.",
                ]);
            }
        } else {
            return response()->json([
                "error" => true,
                'msg' => "No active user found with this credentials",
            ]);
        }
    }
}
