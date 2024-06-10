<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelRentResource extends JsonResource
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
            'name' => $this->name,
            'region' => $this->region,
            'city' => $this->city,
            'country'=>$this->country,
            "price" => (double)$this->price,
            "room_desc" => $this->room_desc,
            "total_reviews" => (int)$this->total_reviews,
            "ratings_value" => (double) $this->ratings_value,
            "available" => (bool) $this->available,
            "distance_away" => (double) $this->distance_away,
            "lat" => (double) $this->lat,
            "lng" => (double)$this->lng,
            'address' => $this->address,
            "object_type" => "rent_hotel",
            "is_favorite" => (bool) $this->isFavorite($request->app_user_id),
            'status' => $this->status,
            "created_at" => $this->created_at,
            "gallery" => ImageResource::collection($this->gallery()),
            "keys" => VehicleKeyResource::collection($this->keys()),
            "rooms" => RoomResource::collection($this->rooms()),
        ];
    }
}
