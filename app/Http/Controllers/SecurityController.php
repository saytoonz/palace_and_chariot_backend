<?php

namespace App\Http\Controllers;

use App\Http\Resources\SecurityResource;
use App\Models\Security;
use App\Models\SecurityRequest;
use App\Models\SecurityClientType;
use App\Traits\ApiResponseTrait;
use App\Traits\AppUserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SecurityController extends Controller
{
    use ApiResponseTrait;
    use AppUserTrait;

    function getAllSecurities()
    {
        $securities = Security::where('status', 'active')->paginate();
        return json_decode($this->ApiResponse(true, 'security', null, $securities, true)->getContent());
    }

    function getSecurityClientType()
    {
        $types = SecurityClientType::where('status', 'active')->get();
        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => $types,
        ]);
    }


    public function createSecurityRequest(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => ['required', 'max:255', 'string'],
                'last_name' => ['required', 'max:255', 'string'],
                'email' => ['required', 'max:255', 'email', 'string'],
                'country' => ['required', 'max:255', 'string'],
                'country_code' => ['required', 'max:255', 'string'],
                'phone' => ['required', 'max:255', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }


       if(isset($request->app_user_id)){ $appUser =  $this->getAppUserWithUserId($request->app_user_id);
        if (!$appUser)  return response()->json([
            "error" => true,
            'msg' => "User not found",
        ]);}



        if ($request->security_client_type_id != null) {
            $secType =  SecurityClientType::find($request->security_client_type_id);
            if ($secType == null)  return response()->json([
                "error" => true,
                'msg' => "Unknown security client type",
            ]);
        }

        //Create a new Security Request
        $secRequest = SecurityRequest::create($request->all());
        if ($secRequest) {
            return response()->json([
                'error' => false,
                'msg' => "success",
                'data' => new SecurityResource($secRequest->refresh()),
            ]);
        } else {
            return response()->json([
                'error' => true,
                'msg' => "An error occurred while processing the request",
            ]);
        }
    }
}
