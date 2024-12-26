<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeddingPackageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'is_popular' => $this->is_popular,
            'thumbnail' => $this->thumbnail,
            'about' => $this->about,
            'city' => new CityResource($this->whenLoaded('city')),
            'photos' => WeddingPhotoResource::collection($this->whenLoaded('photos')),
            'weddingOrganizer' => new WeddingOrganizerResource($this->whenLoaded('weddingOrganizer')),
            'weddingBonusPackages' => WeddingBonusPackageResource::collection($this->whenLoaded('weddingBonusPackages')), // Ensure 'whenLoaded' is used
            'weddingTestimonials' => WeddingTestimonialResource::collection($this->whenLoaded('weddingTestimonials')),
        ];
    }
}

