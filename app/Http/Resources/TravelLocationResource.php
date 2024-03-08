<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelLocationResource extends JsonResource
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
            'abrv_name' => $this->abrv_name,
            'airport' => $this->airport,
            "can_dropoff" => (bool)$this->can_dropoff,
            "can_pick_up" => (bool)$this->can_pick_up,
            "can_provide_security" => (bool)$this->can_provide_security,
            "can_provide_tour" => (bool)$this->can_provide_tour,
            "can_provide_accommodation" => (bool)$this->can_provide_accommodation,
            "can_provide_rentals" => (bool)$this->can_provide_rentals,
            'status' => $this->status,
            "is_favorite" => (bool) $this->isFavorite($request->app_user_id),
            "gallery" => ImageResource::collection($this->gallery()),
        ];
    }
}
