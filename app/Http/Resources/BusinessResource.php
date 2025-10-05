<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category->name,
            'description' => $this->description,
            'logo_url' => $this->logo_path ? asset('storage/' . $this->logo_path) : null,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'created_at' => $this->created_at,
        ];
    }
}
