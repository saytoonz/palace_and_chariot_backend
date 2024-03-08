<?php

namespace App\Http\Controllers;

use App\Http\Resources\TravelLocationResource;
use App\Models\TravelLocations;
use App\Models\TravelRequest;
use App\Traits\AppUserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TravelController extends Controller
{
    use AppUserTrait;
    //
    function getLocations()
    {
        $data = TravelLocations::where('status', 'active')->get();
        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => TravelLocationResource::collection($data),
        ]);
    }

    function createtravelRequest(Request $request)
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
                'depart_location_id' => ['required', 'int'],
                'depart_date' => ['required', 'max:255', 'string'],
                'return_location_id' => ['required', 'int'],
                'return_date' => ['required', 'max:255', 'string'],
                'children' => ['required', 'int'],
                'adults' => ['required', 'int'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }


        if (isset($request->app_user_id) && $request->app_user_id > 0) {
            $appUser =  $this->getAppUserWithUserId($request->app_user_id);
            if (!$appUser)  return response()->json([
                "error" => true,
                'msg' => "User not found",
            ]);
        }

  //Create a new Travel Request
  $travelReq = TravelRequest::create($request->all());
  if ($travelReq) {
      return response()->json([
          'error' => false,
          'msg' => "success",
          'data' => $travelReq->refresh(),
      ]);
  } else {
      return response()->json([
          'error' => true,
          'msg' => "An error occurred while processing the request",
      ]);
  }
    }
}
