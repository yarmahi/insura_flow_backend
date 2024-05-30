<?php

namespace App\Http\Controllers\Api;

use App\Models\Vehicle;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\VehicleResource;
use App\Models\PlanType;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function index()
    {
        return VehicleResource::collection(Vehicle::with(['customer', 'planTypes'])->paginate());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number',
            'engine_number' => 'required|string|max:255|unique:vehicles,engine_number',
            'chassis_number' => 'required|string|max:255|unique:vehicles,chassis_number',
            'model' => 'required|string|max:255',
            'type_of_body' => 'required|string|max:255',
            'horse_power' => 'required|string|max:255',
            'year_manufactured' => 'required|string|max:4',
            'year_of_purchased' => 'required|string|max:4|gte:year_manufactured',
            'passenger_carrying_capacity' => 'required|string|max:255',
            'goods_carrying_capacity' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $vehicle = Vehicle::create($request->all());

        return new VehicleResource($vehicle);
    }

    public function show(Vehicle $vehicle)
    {
        return new VehicleResource($vehicle->load(['customer', 'planTypes']));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number,' . $vehicle->id,
            'engine_number' => 'required|string|max:255|unique:vehicles,engine_number,' . $vehicle->id,
            'chassis_number' => 'required|string|max:255|unique:vehicles,chassis_number,' . $vehicle->id,
            'model' => 'required|string|max:255',
            'type_of_body' => 'required|string|max:255',
            'horse_power' => 'required|string|max:255',
            'year_manufactured' => 'required|string|max:4',
            'year_of_purchased' => 'required|string|max:4|gte:year_manufactured',
            'passenger_carrying_capacity' => 'required|string|max:255',
            'goods_carrying_capacity' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $vehicle->update($request->all());

        return new VehicleResource($vehicle);
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return response()->json(null, 204);
    }

    public function linkPlanType(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'plan_type_id' => 'required|exists:plan_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $planType = PlanType::find($request->input('plan_type_id'));

        if (!$vehicle->planTypes->contains($planType->id)) {
            $vehicle->planTypes()->attach($planType->id);
        }

        return new VehicleResource($vehicle->load('planTypes'));
    }

    public function unlinkPlanType(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'plan_type_id' => 'required|exists:plan_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $planType = PlanType::find($request->input('plan_type_id'));

        if ($vehicle->planTypes->contains($planType->id)) {
            $vehicle->planTypes()->detach($planType->id);
        }

        return new VehicleResource($vehicle->load('planTypes'));
    }
}
