<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerVehicleResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'vehicle_id' => $this->vehicle_id,
            'plan_array' => $this->plan_array,
            'premium_amount' => $this->premium_amount,
            'payment_term' => $this->payment_term,
            'next_payment' => $this->next_payment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'invoices' => InvoiceResource::collection($this->whenLoaded('invoices')),
            'claims' => ClaimResource::collection($this->whenLoaded('claims')),
        ];
    }
}
