<?php

namespace App\Http\Controllers;

use App\Http\Resources\HotelRentResource;
use App\Models\HotelRent;
use App\Models\ApartmentRequest;
use App\Models\HotelRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelRentController extends Controller
{
    function getHotelsServices()
    {
        $data = HotelRent::where('status', 'active')->orderBy('id', 'desc')->get();

        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' =>  HotelRentResource::collection($data),
        ]);
    }


    public function createRequest(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'rent_hotel_id' => ['required', 'int'],
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


        //Create a new Request
        $secRequest = HotelRequest::create($request->all());
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
