<?php

namespace App\Http\Resources;

use App\Models\DashboardUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
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
            'created_at' => $this->created_at,
            'country' => is_null($this->country) || $this->country == ''? 'Unknown' : $this->country,
            'device' => $this->device,
            'activity' => $this->activity,
            'dashboard_user' => new DashboardUserResources(DashboardUser::find($this->dashboard_user_id)),
        ];
    }
}
