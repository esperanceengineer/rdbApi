<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticProvinceResource extends JsonResource
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
            'FullName' => $this->fullname,
            'Image' => $this->image,
            'Percentage' => $this->percentage,
            'Province' => $this->province,
            'Vote' => $this->vote,
            'Absension' => $this->absension,
            'InvalidBulletin' => $this->invalid_bulletin,
            'ExpressedSuffrage' => $this->expressed_suffrage,
            'TotalRegistered' => $this->total_registered,
        ];
    }
}
