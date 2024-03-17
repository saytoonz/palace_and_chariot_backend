<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventServiceRentResource extends JsonResource
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
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            "price" => (float)$this->price,
            "capacity" => $this->capacity,
            "total_reviews" => $this->total_reviews,
            "ratings_value" => (float) $this->ratings_value,
            "available" => (bool) $this->available,
            "distance_away" => (float) $this->distance_away,
            "lat" => (float) $this->lat,
            "lng" => (float)$this->lng,
            'address' => $this->address,
            "is_favorite" => (bool) $this->isFavorite($request->app_user_id),
            'status' => $this->status,
            "created_at" => $this->created_at,
            "gallery" => ImageResource::collection($this->gallery()),
            "keys" => VehicleKeyResource::collection($this->keys()),
        ];
    }
}
