<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
            'image' => $this->image,
            'transmision' => $this->transmision,
            'seat' => $this->seat,
            'cost_per_day' => $this->cost_per_day,
            'location' => $this->location,
            'available' => $this->available,
        ];
    }
}
