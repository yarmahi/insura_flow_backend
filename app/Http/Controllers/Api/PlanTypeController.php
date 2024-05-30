<?php

namespace App\Http\Controllers\Api;

use App\Models\PlanType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PlanTypeResource;
use Illuminate\Support\Facades\Validator;

class PlanTypeController extends Controller
{
    public function index()
    {
        return PlanTypeResource::collection(PlanType::with('vehicles')->paginate());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $planType = PlanType::create($request->all());

        return new PlanTypeResource($planType);
    }

    public function show(PlanType $planType)
    {
        return new PlanTypeResource($planType->load('vehicles'));
    }

    public function update(Request $request, PlanType $planType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $planType->update($request->all());

        return new PlanTypeResource($planType);
    }

    public function destroy(PlanType $planType)
    {
        $planType->delete();

        return response()->json(null, 204);
    }
}
