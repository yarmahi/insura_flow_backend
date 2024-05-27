<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        return CustomerResource::collection(Customer::with(['user', 'vehicles'])->paginate());
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer->load(['user', 'vehicles']));
    }

}
