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
            'FullName' => $this->fullname,
            'Image' => $this->image,
            'ImageUrl' => $this->image_url,
            'Percentage' => $this->image_url,
        ];
    }
}
