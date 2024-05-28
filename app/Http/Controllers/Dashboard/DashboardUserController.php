<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\DashboardUser;
use Illuminate\Http\Request;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Traits\ApiResponseTrait;
use App\Traits\CountryTrait;

class DashboardUserController extends Controller
{
    use ImageTrait;
    use CountryTrait;
    use ApiResponseTrait;

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


        $country = $this->getCountry($request);
        AccessLog::create([
            'dashboard_user_id' => $dashUser->id,
            'country' => $country,
            'device' => $request->header('User-Agent'),
        ]);

        $dashUser->last_login = date('Y-m-d H:i:s');
        $dashUser->save();

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
                'msg' => 'Email has been sent to ' . $request->email . ' with pasword update code (Code: ' . $fogetPassCode . ')',
            ]);
        } else {
            return response()->json([
                "error" => true,
                'msg' => "No active user found with this credentials",
            ]);
        }
    }


    public function  resetPassword(Request $request)
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
                    'msg' => 'Password changed successfully',
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

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'dashboard_user_id' => ['required',  'int'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        //Create a new app user
        $dashUser = DashboardUser::where('id', $request->dashboard_user_id)->where('is_deleted', false)->first();

        if ($dashUser) {
            //Updates user
            $usrArray = [];
            if ($request->first_name) {
                $usrArray["first_name"] = $request->first_name;
            }

            if ($request->middle_name) {
                $usrArray["middle_name"] = $request->middle_name;
            }


            if ($request->last_name) {
                $usrArray["last_name"] = $request->last_name;
            }

            if ($request->phone) {
                $usrArray["phone"] = $request->phone;
            }

            if ($request->date_of_birth) {
                $usrArray["date_of_birth"] = $request->date_of_birth;
            }


            if ($request->request_confirmation_notifiction != null) {
                $usrArray["request_confirmation_notifiction"] = $request->request_confirmation_notifiction == 'true' ? 1 : 0;
            }

            if ($request->request_change_notifiction != null) {
                $usrArray["request_change_notifiction"] = $request->request_change_notifiction == 'true' ? 1 : 0;
            }

            if ($request->email_notifiction != null) {
                $usrArray["email_notifiction"] = $request->email_notifiction == 'true'  ? 1 : 0;
            }



            if ($request->image) {
                $avatar = $this->uploadAvatar($request, 'image', 'avatar_' . $dashUser->id . date('Y-m-d H:i:s'));
                if ($avatar) {
                    $usrArray["image_url"] = $avatar;
                }
            }

            $dashUser->update($usrArray);

            return response()->json([
                'error' => false,
                'msg' => "success",
                'data' => $dashUser,
            ]);
        } else {
            return response()->json([
                'error' => true,
                'msg' => "No user found",
            ]);
        }
    }

    public function  updatePassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'max:255', 'email', 'string'],
                'old_password' => ['required', 'min:6', 'max:255', 'string'],
                'new_password' => ['required', 'min:6', 'max:255', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        $dashUser = DashboardUser::where('email', $request->email)
            ->where('status', 'active')
            ->where('password',  md5(sha1($request->old_password)))
            ->where('is_deleted', false)
            ->first();

        if ($dashUser) {
            $dashUser->password = md5(sha1($request->new_password));
            $dashUser->rest_pass_code = null;
            $dashUser->save();

            return response()->json([
                "error" => false,
                'msg' => 'Password changed successfully',
            ]);
        } else {
            return response()->json([
                "error" => true,
                'msg' => "User credentials do not match",
            ]);
        }
    }

    function adminGetActiveUsers($adminId)
    {
        $dashUsers = DashboardUser::where('id', $adminId)
            ->where('access', 'admin')
            ->where('status', 'active')
            ->where('is_deleted', 0)->first();

        if (!$dashUsers) {
            return response()->json([
                "error" => true,
                'msg' => "Invalid user or limited access"
            ]);
        }

        $dashUsers = DashboardUser::where('id', '!=', $adminId)->where('status', 'active')->where('is_deleted', 0)->paginate();
        return $this->ApiResponse(true, 'dashboar_user', null, $dashUsers);
    }



    function adminUpdateUser(Request $request, $adminId)
    {
        $loggedInUser = DashboardUser::where('id', $adminId)
            ->where('access', 'admin')
            ->where('status', 'active')
            ->where('is_deleted', 0)->first();

        if (!$loggedInUser) {
            return response()->json([
                "error" => true,
                'msg' => "Invalid user or limited access"
            ]);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'dashboard_user_id' => ['required',  'int'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        //Create a new app user
        $dashUser = DashboardUser::where('id', $request->dashboard_user_id)->where('is_deleted', false)->first();

        if ($dashUser) {
            //Updates user
            $usrArray = [];
            if ($request->first_name) {
                $usrArray["first_name"] = $request->first_name;
            }

            if ($request->middle_name) {
                $usrArray["middle_name"] = $request->middle_name;
            }


            if ($request->last_name) {
                $usrArray["last_name"] = $request->last_name;
            }

            if ($request->phone) {
                $usrArray["phone"] = $request->phone;
            }

            if ($request->date_of_birth) {
                $usrArray["date_of_birth"] = $request->date_of_birth;
            }
            if ($request->access) {
                $usrArray["access"] = $request->access;
            }

            if ($request->status) {
                $usrArray["status"] = $request->status;
            }
            if ($request->email) {
                $usrArray["email"] = $request->email;
            }
            if ($request->gender) {
                $usrArray["gender"] = $request->gender;
            }

            if ($request->employee_id) {
                $usrArray["employee_id"] = $request->employee_id;
            }


            if ($request->image) {
                $avatar = $this->uploadAvatar($request, 'image', 'avatar_' . $dashUser->id . date('Y-m-d H:i:s'));
                if ($avatar) {
                    $usrArray["image_url"] = $avatar;
                }
            }

            $dashUser->update($usrArray);

            return response()->json([
                'error' => false,
                'msg' => "success",
                'data' => $dashUser,
            ]);
        } else {
            return response()->json([
                'error' => true,
                'msg' => "No user found",
            ]);
        }
    }




    function adminGetRequestedUsers($adminId)
    {
        $dashUsers = DashboardUser::where('id', $adminId)
            ->where('access', 'admin')
            ->where('status', 'active')
            ->where('is_deleted', 0)->first();

        if (!$dashUsers) {
            return response()->json([
                "error" => true,
                'msg' => "Invalid user or limited access"
            ]);
        }

        $dashUsers = DashboardUser::where('id', '!=', $adminId)->where('status', 'pending')->where('is_deleted', 0)->paginate();
        return $this->ApiResponse(true, 'dashboar_user', null, $dashUsers);
    }




    function adminAcceptUser(Request $request, $adminId)
    {
        $dashUsers = DashboardUser::where('id', $adminId)
            ->where('access', 'admin')
            ->where('status', 'active')
            ->where('is_deleted', 0)->first();

        if (!$dashUsers) {
            return response()->json([
                "error" => true,
                'msg' => "Invalid user or limited access"
            ]);
        }


        $validator = Validator::make(
            $request->all(),
            [
                'dashboard_user_id' => ['required',  'int'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        //Create a new app user
        $dashUser = DashboardUser::where('id', '!=', $adminId)->where('id', $request->dashboard_user_id)->where('is_deleted', false)->first();
        if ($dashUser) {
            $dashUser->update([
                'status' => 'active',
            ]);

            return response()->json([
                "error" => false,
                'msg' => 'success',
                'data' => $dashUser,
            ]);
            return $dashUser;
        } else {
            return response()->json([
                "error" => true,
                'msg' => 'You cannot update yourself.'
            ]);
        }
    }

    function adminRejectUser(Request $request, $adminId)
    {
        $dashUsers = DashboardUser::where('id', $adminId)
            ->where('access', 'admin')
            ->where('status', 'active')
            ->where('is_deleted', 0)->first();

        if (!$dashUsers) {
            return response()->json([
                "error" => true,
                'msg' => "Invalid user or limited access"
            ]);
        }


        $validator = Validator::make(
            $request->all(),
            [
                'dashboard_user_id' => ['required',  'int'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        $dashUser = DashboardUser::where('id', '!=', $adminId)
            ->where('id', $request->dashboard_user_id)
            ->where('is_deleted', false)->first();
        if ($dashUser) {
            $dashUser->update([
                'status' => 'inactive',
                'is_deleted' => true,
            ]);
            return response()->json([
                "error" => false,
                'msg' => 'success',
                'data' => $dashUser,
            ]);
        } else {
            return response()->json([
                "error" => true,
                'msg' => 'You cannot update yourself.'
            ]);
        }
    }
}
