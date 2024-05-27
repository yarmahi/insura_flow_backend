<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
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
            'plate_number' => $this->plate_number,
            'engine_number' => $this->engine_number,
            'chassis_number' => $this->chassis_number,
            'model' => $this->model,
            'type_of_body' => $this->type_of_body,
            'horse_power' => $this->horse_power,
            'year_manufactured' => $this->year_manufactured,
            'year_of_purchased' => $this->year_of_purchased,
            'passenger_carrying_capacity' => $this->passenger_carrying_capacity,
            'goods_carrying_capacity' => $this->goods_carrying_capacity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'customer' => CustomerResource::collection($this->whenLoaded('customer')),
        ];
    }
}
