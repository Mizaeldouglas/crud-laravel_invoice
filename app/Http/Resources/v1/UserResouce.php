<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'full_name' => $this->firstName . ' ' . $this->lastName,
            'email' => $this->email,
        ];
    }
}
