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
        return [
            "id" => $this->id,
            "name" => $this->name,
            "model" => $this->model,
            "color" => $this->color,
            "price" =>(double) $this->price,
            "discount" =>(double) $this->discount,
            "driver_fee" => (double) $this->driver_fee,
            "distance_away"  =>(double) $this->distance_away,
            "available" => (bool) $this->available,
            "type" => $this->type,
            "status" => $this->status,
            "ratings_value" => (double)$this->ratings_value,
            "object_type" => "rent_vehicle",
            "is_favorite" => (bool) $this->isFavorite($request->app_user_id),
            "make" => $this->make,
            "gallery" => ImageResource::collection($this->gallery()),
            "keys" => VehicleKeyResource::collection( $this->keys()),
        ];
    }
}
