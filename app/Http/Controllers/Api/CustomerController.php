<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;
use App\Models\Agent;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        return CustomerResource::collection(Customer::with(['user', 'vehicles', 'agent'])->paginate());
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer->load(['user', 'vehicles', 'agent']));
    }

    public function linkAgent(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required|exists:agents,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check if the customer is linked to any agent
        if ($customer->agent_id == $request->input('agent_id')) {
            return response()->json(['message' => 'This customer is already linked to this agent.'], 404);
        }

        $agent = Agent::find($request->input('agent_id'));
        $customer->agent()->associate($agent);
        $customer->save();

        // add event to notification table 
        Notification::create([
            'user_id' => $agent->user_id,
            'title' => 'New Customer added',
            'content' => "A new Customer ($customer->fname $customer->lname) has been added to your account. Please review it as soon as possible.",
        ]);

        return new CustomerResource($customer->load(['user', 'vehicles', 'agent']));
    }

    public function unlinkAgent(Request $request, Customer $customer)
    {
        // Check if the customer is linked to any agent
        if ($customer->agent_id === null) {
            return response()->json(['message' => 'This customer is not linked to any agent.'], 404);
        }

        $agent = $customer->agent;

        $customer->agent()->dissociate();
        $customer->save();

        // add event to notification table 
        Notification::create([
            'user_id' => $agent->user_id,
            'title' => 'Customer has been removed',
            'content' => "The customer $customer->fname $customer->lname has been successfully removed from your account.",
        ]);

        return new CustomerResource($customer->load(['user', 'vehicles', 'agent']));
    }

}
