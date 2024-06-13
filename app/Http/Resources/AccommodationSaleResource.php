<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccommodationSaleResource extends JsonResource
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
            "region" => $this->region,
            "city" => $this->city,
            "price" => (float) $this->price,
            "available" => (bool) $this->available,
            "status" => $this->status,
            "ratings_value" => (float)$this->ratings_value,
            "object_type" => "sale_accomm",
            "discount" => (double) $this->discount,
            "is_favorite" => (bool) $this->isFavorite($request->app_user_id),
            "gallery" => ImageResource::collection($this->gallery()),
            "keys" => VehicleKeyResource::collection($this->keys()),
            "text_keys" => VehicleTextKeyResource::collection($this->textKeys()),

        ];
    }
}
