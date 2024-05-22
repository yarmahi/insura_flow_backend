<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClaimResource extends JsonResource
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
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'customer_vehicle_id' => $this->customer_vehicle_id,
            'description' => $this->description,
            'time_of_accident' => $this->time_of_accident,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'customer_vehicle' => new CustomerVehicleResource($this->whenLoaded('customerVehicle')),
            'agent_claims' => AgentClaimResource::collection($this->whenLoaded('agentClaims')),
            'claim_witnesses' => ClaimWitnessResource::collection($this->whenLoaded('claimWitnesses')),
            'claim_photos' => ClaimPhotoResource::collection($this->whenLoaded('claimPhotos')),
        ];
    }
}
