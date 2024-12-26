<?php

namespace App\Http\Resources\Api;


use Illuminate\Http\Request;
use App\Http\Resources\Api\BonusPackageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WeddingBonusPackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bonusPackage' => new BonusPackageResource($this->whenLoaded('bonusPackage')), // Correctly reference BonusPackage
        ];
    }
}

