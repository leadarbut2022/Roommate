<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id??0,
            'titel' => $this->titel??'',
            'details' => $this->details??'',
            'governorate' => $this->governorate??'',
            'google_maps' => $this->google_maps??'',
            'location_details' => $this->location_details??'',
            'price' => $this->price??'',
            'type' => $this->type??'',
            'created_at' => $this->created_at??'',
            'updated_at' => $this->updated_at??'',
            'images' => $this->images->map(function ($images) {
                return [
                    'id' => $images->id??0,
                    'img' => $images->img??'',
                  
                ];
            }),
            'user' => [
                'id' => $this->user->id ?? 0,
                'name' => $this->user->name ?? '',
                'phone_number' => $this->user->phone_number ?? '',
             
            ],
        ];
    }
}
