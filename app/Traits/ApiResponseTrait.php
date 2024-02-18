<?php

namespace  App\Traits;

use App\Http\Resources\AppUserResources;

trait ApiResponseTrait
{

    public function ApiResponse(bool $status, $type, $message, $paginateData, $showHeaders = true)
    {
        $dataR = match($type){
            // 'comment'=>CommentResource::collection($paginateData),
            // 'video' => VideoResource::collection($paginateData),
            // 'like' => LikeResource::collection($paginateData),
            // 'channel'=>ChannelResource::collection($paginateData),
            'app_user'=>AppUserResources::collection($paginateData),
            // 'video-with-channel'=>VideoWithChannelResource::collection($paginateData),
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
                'number_per_page' => $paginateData->perPage()
            ]
        ]));
    }

}
