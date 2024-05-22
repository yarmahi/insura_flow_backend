<?php

namespace App\Http\Controllers\Api;

use App\Models\Claim;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimResource;

class ClaimController extends Controller
{
    public function index()
    {
        return ClaimResource::collection(Claim::with(['customerVehicle', 'agentClaims', 'claimWitnesses', 'claimPhotos'])->paginate());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'customer_vehicle_id' => 'required|exists:customer_vehicles,id',
            'description' => 'nullable|string',
            'time_of_accident' => 'required|date',
            'status' => 'required|in:pending,approved,declined',
        ]);

        $claim = Claim::create($validated);

        return new ClaimResource($claim);
    }

    public function show(Claim $claim)
    {
        return new ClaimResource($claim->load(['customerVehicle', 'agentClaims', 'claimWitnesses', 'claimPhotos']));
    }

    public function update(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'latitude' => 'sometimes|required|string',
            'longitude' => 'sometimes|required|string',
            'customer_vehicle_id' => 'sometimes|required|exists:customer_vehicles,id',
            'description' => 'nullable|string',
            'time_of_accident' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:pending,approved,declined',
        ]);

        $claim->update($validated);

        return new ClaimResource($claim);
    }

    public function destroy(Claim $claim)
    {
        $claim->delete();

        return response()->noContent();
    }
}

