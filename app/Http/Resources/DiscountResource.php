<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
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
            'campaign_id' => $this->campaign_id,
            'campaign_name' => $this->campaign->name,
            'name' => $this->name,
            'value' => $this->value,
            'type' => $this->type,
        ];
    }
}
