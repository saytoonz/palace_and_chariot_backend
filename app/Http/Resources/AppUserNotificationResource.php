<?php

namespace App\Http\Resources;

use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppUserNotificationResource extends JsonResource
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
            "app_user_id" => (int)$this->app_user_id,
            "chat_box" => (bool)$this->chat_box,
            "travel_ideas" => (bool)$this->travel_ideas,
            "rentals" => (bool)$this->rentals,
            "security" => (bool)$this->security,
            "sales" => (bool)$this->sales,
            "upcoming_deals" => (bool)$this->upcoming_deals,
        ];
    }
}
