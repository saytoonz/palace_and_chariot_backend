<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourismResource extends JsonResource
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
            'title' => $this->title,
            'overview' => $this->overview,
            'price' => (double)$this->price,
            "ratings_value" => (double)$this->ratings_value,
            "total_ratings" => (double)$this->total_ratings,
            "available_time" => $this->available_time,
            "free_cancellation" => (bool)$this->free_cancellation,
            "can_pick_up" => (bool)$this->can_pick_up,
            "can_provide_security" => (bool)$this->can_provide_security,
            'status' => $this->status,
            "object_type" => "tour",
            "is_favorite" => (bool) $this->isFavorite($request->app_user_id),
            "gallery" => ImageResource::collection($this->gallery()),
        ];
    }
}
