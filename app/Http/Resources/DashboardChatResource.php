<?php

namespace App\Http\Resources;

use App\Models\AppUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardChatResource extends JsonResource
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
            'from' =>(int) $this->from,
            "object_id" => (int)$this->object_id,
            "object_type" => $this->object_type,
            'owner' => (int)$this->owner,
            "message" => $this->message,
            "type" => $this->type,
            "unread" => (bool)$this->unread,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'status' => $this->status,
            "object" => $this->object_type == 'sale_vehicle' ? new VehicleSaleResource($this->saleVehicle) : new AccommodationSaleResource($this->saleAccomm),
            "app_user" => new AppUserResources(AppUser::find($this->owner)),
        ];
    }
}
