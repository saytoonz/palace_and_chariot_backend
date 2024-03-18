<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApartmentRentResource;
use App\Models\ApartmentRent;
use App\Models\ApartmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApartmentRentController extends Controller
{
    function getApartmentServices()
    {
        $data = ApartmentRent::where('status', 'active')->get();

        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' =>  ApartmentRentResource::collection($data),
        ]);
    }


    public function createRequest(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'rent_apartment_id' => ['required', 'int'],
                'first_name' => ['required', 'max:255', 'string'],
                'last_name' => ['required', 'max:255', 'string'],
                'email' => ['required', 'max:255', 'email', 'string'],
                'country' => ['required', 'max:255', 'string'],
                'country_code' => ['required', 'max:255', 'string'],
                'phone' => ['required', 'max:255', 'string'],
                'rooms_ids' => ['required', 'max:255', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }


        //Create a new Security Request
        $secRequest = ApartmentRequest::create($request->all());
        if ($secRequest) {
            return response()->json([
                'error' => false,
                'msg' => "success",
                'data' => $secRequest->refresh(),
            ]);
        } else {
            return response()->json([
                'error' => true,
                'msg' => "An error occurred while processing the request",
            ]);
        }
    }
}
