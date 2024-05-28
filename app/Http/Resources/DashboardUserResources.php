<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardUserResources extends JsonResource
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
            "middle_name" => $this->middle_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "phone" => $this->phone,
            "date_of_birth" => $this->date_of_birth,
            "last_login" => $this->last_login,
            "image_url" => $this->image_url  ?env('APP_URL').$this->image_url : $this->image_url,
            "access" => $this->access,
            "status" => $this->status,
            "request_confirmation_notifiction"=> $this->request_confirmation_notifiction == 1,
            "request_change_notifiction"=> $this->request_change_notifiction == 1,
            "email_notifiction"=> $this->email_notifiction == 1,
            "is_deleted"=> $this->is_deleted == 1,
        ];
    }
}
