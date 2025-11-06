<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
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
            'group_id' => $this->group_id,
            'group_name' => $this->group->name,
            'name' => $this->name,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'discounts'  => $this->whenLoaded('discounts', function () {
                return $this->resource->discounts->map(function ($discount) {
                    return new DiscountResource($discount);
                });
            }),
        ];
    }
}
