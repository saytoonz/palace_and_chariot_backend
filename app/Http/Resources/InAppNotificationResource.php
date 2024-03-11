<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InAppNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $arrayData = parent::toArray($request);


        $attributes = array_keys($arrayData);
        for ($i = 0; $i < count($attributes); $i++) {
            $arrayData[$attributes[$i]] =  [
                'id' =>  $arrayData[$attributes[$i]]['id'],
                'title' =>  $arrayData[$attributes[$i]]['title'],
                'body' =>  $arrayData[$attributes[$i]]['body'],
                'object_id' =>  (int) $arrayData[$attributes[$i]]['object_id'],
                'object_type' =>  $arrayData[$attributes[$i]]['object_type'],
                'image' =>  env('APP_URL').$arrayData[$attributes[$i]]['image'],
                'status' =>  $arrayData[$attributes[$i]]['status'],
                'created_at' =>  $arrayData[$attributes[$i]]['created_at'],
            ];
        }





        return $arrayData;
    }
}
