<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'from' => $this->from,
            "object_id" => $this->object_id,
            "object_type" => $this->object_type,
            'owner' => $this->owner,
            "message" => $this->message,
            "type" => $this->type,
            "unread" => (bool)$this->unread,
            "created_at" => $this->created_at,
            'status' => $this->status,
            "object" => $this->object_type == 'sale_vehicle' ? new VehicleSaleResource($this->saleVehicle) : new AccommodationSaleResource($this->saleAccomm),
        ];
    }
}
