<?php

namespace App\Http\Controllers\Api;

use App\Models\Claim;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimResource;
use App\Models\Agent;
use Illuminate\Support\Facades\Validator;


class ClaimController extends Controller
{
    public function index()
    {
        return ClaimResource::collection(Claim::with(['vehicle', 'agent', 'claimWitnesses', 'claimPhotos'])->paginate());
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
        return new ClaimResource($claim->load(['vehicle', 'agent', 'claimWitnesses', 'claimPhotos']));
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

    public function linkAgent(Request $request, Claim $claim)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required|exists:agents,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $agent = Agent::findOrFail($request->agent_id);

        // Check if the claim is already linked to the agent
        if ($claim->agent_id === $agent->id) {
            return response()->json(['message' => 'This claim is already linked to this agent.'], 409);
        }

        // Link the claim to the agent
        $claim->update([
            "agent_id" => $agent->id
        ]);

        return response()->json([
            'message' => 'Claim linked to agent successfully.',
            'claim' => $claim,
        ], 200);
    }

    public function unlinkAgent(Request $request, Claim $claim)
    {
        // Check if the claim is linked to any agent
        if ($claim->agent_id === null) {
            return response()->json(['message' => 'This claim is not linked to any agent.'], 404);
        }

        // Unlink the claim from the agent
        $claim->update([
            "agent_id" => null
        ]);

        return response()->json(['message' => 'Claim unlinked from agent successfully.'], 200);
    }
}

