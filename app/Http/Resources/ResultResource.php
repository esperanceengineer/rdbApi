<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
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
            'Absension' => $this->absension,
            'InvalidBulletin' => $this->invalid_bulletin,
            'ExpressedSuffrage' => $this->expressed_suffrage,
            'TotalRegistered' => $this->total_registered,
            'UserId' => $this->user_id,
            'CenterId' => $this->center_id,
        ];
    }
}
