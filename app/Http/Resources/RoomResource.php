<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => env('APP_URL') . $this->image,
            'price' => (double) $this->price,
            'discount' => (double) $this->discount,
            'object_id' => (int) $this->object_id,
            'object_type' => $this->object_type,
            'status' => $this->status,
            "keys" => VehicleKeyResource::collection($this->keys()),
        ];
    }
}
