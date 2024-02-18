<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppUserResources extends JsonResource
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
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "username" => $this->username,
            "fuid" => $this->fuid,
            "email" => $this->email,
            "country" => $this->country,
            "country_code" => $this->country_code,
            "phone" => $this->phone,
            "date_of_birth" => $this->date_of_birth,
            "gender" => $this->gender,
            "image" => $this->image,
            "language" => $this->language,
            "is_active"=> $this->is_active == 1,
            "is_banned"=> $this->is_banned == 1,
            "notiSetting" => new AppUserNotificationResource($this->notiSetting),
        ];
    }
}
