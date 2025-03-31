<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'Profile' => $this->profile,
            'Username' => $this->username,
            'Email' => $this->email,
            'Name' => $this->name,
            'FirstName' => $this->firstname,
            'ProfileLabel' => $this->profile_label,
            'Password' => $this->password,
            'Image' => $this->image,
            'IsLocked' => $this->is_locked,
            'Office' => $this->office,
            'HasPasswordTemp' => $this->has_password_temp,
            'CreatedAt' => $this->created_at,
            'UpdatedAt' => $this->updated_at,
            'DeletedAt' => $this->deleted_at,
        ];

    }
}
