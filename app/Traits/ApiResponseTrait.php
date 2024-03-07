<?php

namespace  App\Traits;

use App\Http\Resources\AppUserResources;
use App\Http\Resources\SecurityResource;
use App\Http\Resources\VehicleRentResource;

trait ApiResponseTrait
{

    public function ApiResponse(bool $status, $type, $message, $paginateData, $showHeaders = true)
    {
        $dataR = match($type){
            'security'=>SecurityResource::collection($paginateData),
            'app_user'=>AppUserResources::collection($paginateData),
            'vehicle_rent'=>VehicleRentResource::collection($paginateData),
        };

        $headers = [];

        if($showHeaders){
            $headers['error'] = $status == true ? false : true;
            $headers['msg'] = $message ?? 'success' ;
        }

        return response()->json(array_merge($headers, [
            'data' => $dataR,
            'paginate' => $paginateData == NULL ? NULL : [
                'previous_page_url' => $paginateData->appends(request()->input())->previousPageUrl(),
                'next_page_url' => $paginateData->appends(request()->input())->nextPageUrl(),
                'number_per_page' => $paginateData->perPage(),
                'total_items' => $paginateData->total(),
            ]
        ]));
    }

}
