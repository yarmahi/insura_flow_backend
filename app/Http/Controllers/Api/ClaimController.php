<?php

namespace App\Http\Controllers\Api;

use App\Models\Claim;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimResource;
use App\Models\Agent;
use App\Models\Notification;
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
            'agent_id' => 'nullable|exists:agents,id',
            'vehicle_id' => 'required|exists:vehicles,id',
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
            'agent_id' => 'nullable|exists:agents,id',
            'vehicle_id' => 'sometimes|required|exists:vehicles,id',
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

        // add event to notification table 
        Notification::create([
            'user_id' => $agent->user_id,
            'title' => 'New Claim added',
            'content' => 'A new claim has been added to your account. Please review it as soon as possible.',
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

        $agent = $claim->agent;

        // Unlink the claim from the agent
        $claim->update([
            "agent_id" => null
        ]);

        // add event to notification table 
        Notification::create([
            'user_id' => $agent->user_id,
            'title' => 'Claim has been removed',
            'content' => 'The claim has been successfully removed from your account.',
        ]);

        return response()->json(['message' => 'Claim unlinked from agent successfully.'], 200);
    }

    public function changeStatus(Request $request, Claim $claim)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,declined',
        ]);

        // Check if the status is same 
        if ($claim->status == $request->input('status')) {
            return response()->json(['message' => 'This claim is already in the specified status.'], 404);
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $claim->status = $request->input('status');
        $claim->save();

        // add event to notification table 
        Notification::create([
            'user_id' => $claim->vehicle->customer->user_id,
            'title' => 'Status Changed',
            'content' => "The claim status has been changed to $claim->status.",
        ]);

        return new ClaimResource($claim);
    }
}

