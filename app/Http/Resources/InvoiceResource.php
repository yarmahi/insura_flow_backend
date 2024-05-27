<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'customer_vehicle_id' => $this->customer_vehicle_id,
            'transaction_number' => $this->transaction_number,
            'amount' => $this->amount,
            'start_data' => $this->start_data,
            'end_data' => $this->end_data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'vehicle' => new CustomerResource($this->whenLoaded('vehicle')),
        ];
    }
}
