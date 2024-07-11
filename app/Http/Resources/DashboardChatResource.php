<?php

namespace App\Http\Resources;

use App\Models\AppUser;
use App\Models\ChatMessage;
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
            'admin' => $this->admin,
            "unread" => (bool)$this->unread,
            "unreads"=> ChatMessage::where(function ($query) {
                $query->where('from', $this->from,);
                $query->orwhere('to', $this->from,);
            })->where('object_id', $this->object_id)->where('object_type', $this->object_type)->count(),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'status' => $this->status,
            "object" => $this->object_type == 'sale_vehicle' ? new VehicleSaleResource($this->saleVehicle) : new AccommodationSaleResource($this->saleAccomm),
            "app_user" => new AppUserResources(AppUser::find($this->owner)),
        ];
    }
}
