<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CenterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->id,
            'Label' => $this->label,
            'Office' => $this->office,
            'LocalityId' => $this->locality_id,
            'Code' => $this->code,
            'CreatedAt' => $this->created_at,
            'UpdatedAt' => $this->updated_at,
        ];
    }
}
