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
            "app_user_id" => $this->app_user_id == 1,
            "chat_box" => $this->chat_box == 1,
            "travel_ideas" => $this->travel_ideas == 1,
            "rentals" => $this->rentals == 1,
            "security" => $this->security == 1,
            "sales" => $this->sales == 1,
            "upcoming_deals" => $this->upcoming_deals == 1,
        ];
    }
}
