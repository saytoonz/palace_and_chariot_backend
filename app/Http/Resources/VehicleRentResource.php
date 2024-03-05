<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleRentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       $array =  parent::toArray($request);
       $array['isFavorite'] = $this->isFavorite($request->app_user_id);
       $array['gallery'] = $this->gallery();
       $array['keys'] = $this->keys();
        return $array;
    }
}
