<?php

namespace App\Http\Controllers;

use App\Models\AccommodationSale;
use App\Models\AccommodationSaleRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccommodationSaleController extends Controller
{
    use ApiResponseTrait;


    function getSaleAccomms()
    {
        $data =  AccommodationSale::orderBy('id', 'desc')->paginate();
        return $this->ApiResponse(true, 'accomm_sale', null, $data, true);
    }



    function createCallBackRequest(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'accommodation_id' => ['required', 'int'],
                'name' => ['required', 'max:255', 'string'],
                'email' => ['required', 'email', 'string'],
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


        //Create a new Accommodation Sale Request
        $rentRequest = AccommodationSaleRequest::create($request->all());
        if ($rentRequest) {
            return response()->json([
                'error' => false,
                'msg' => "success",
                'data' => $rentRequest->refresh(),
            ]);
        } else {
            return response()->json([
                'error' => true,
                'msg' => "An error occurred while processing the request",
            ]);
        }
    }
}
