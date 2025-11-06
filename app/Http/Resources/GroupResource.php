<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'description' => $this->description,
            'cities'  => $this->whenLoaded('cities', function () {
                return $this->resource->cities->map(function ($city) {
                    return new CityResource($city);
                });
            }),
            'active_campaigns' => CampaignResource::collection(
                $this->whenLoaded('activeCampaigns')
            ),

            'past_campaigns' => CampaignResource::collection(
                $this->whenLoaded('pastCampaigns')
            ),
        ];
    }
}
