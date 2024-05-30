<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgentResource;
use App\Http\Resources\ClaimResource;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Claim;


class AgentController extends Controller
{
    public function index()
    {
        return AgentResource::collection(Agent::with(['user', 'claims', 'customers'])->paginate());
    }

    public function show(Agent $agent)
    {        
        return new AgentResource($agent->load(['user', 'claims', 'customers']));
    }

}
