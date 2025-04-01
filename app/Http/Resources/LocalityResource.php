<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocalityResource extends JsonResource
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
            'DepartmentId' => $this->department_id,
            'Centers' => CenterResource::collection($this->whenLoaded('centers')),
            'CreatedAt' => $this->created_at,
            'UpdatedAt' => $this->updated_at,
        ];

    }
}
