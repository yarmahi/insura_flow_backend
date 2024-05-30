<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'user_id' => $this->user_id,
            'is_company' => $this->is_company,
            'fname' => $this->fname,
            'mname' => $this->mname,
            'lname' => $this->lname,
            'license_number' => $this->license_number,
            'wereda' => $this->wereda,
            'phone' => $this->phone,
            'house_no' => $this->house_no,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'vehicles' => VehicleResource::collection($this->whenLoaded('vehicles')),
            'agent' => new AgentResource($this->whenLoaded('agent')),
        ];
    }
}
