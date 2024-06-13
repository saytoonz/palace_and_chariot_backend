<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleSaleResource extends JsonResource
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
            "price" => (float) $this->price,
            "available" => (bool) $this->available,
            "status" => $this->status,
            "ratings_value" => (double)$this->ratings_value,
            "object_type" => "sale_vehicle",
            'discount' => (double)$this->discount,
            'quantity' => (int)$this->quantity,
            "is_favorite" => (bool) $this->isFavorite($request->app_user_id),
            "make" => $this->make,
            "gallery" => ImageResource::collection($this->gallery()),
            "keys" => VehicleKeyResource::collection($this->keys()),
            "text_keys" => VehicleTextKeyResource::collection($this->textKeys()),
        ];
    }
}
