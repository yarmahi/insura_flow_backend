<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserResource;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::paginate());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'type' => 'required|in:0,1,2',
            'fname' => 'required|string',
            'mname' => 'nullable|string',
            'lname' => 'required|string',
            'phone' => 'required|string',
            'is_company' => 'nullable|required_if:type,0|boolean',
            'license_number' => 'nullable|required_if:type,0|string',
            'wereda' => 'nullable|required_if:type,0|string',
            'house_no' => 'nullable|required_if:type,0|string',
        ]);

        // return $validated;
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => $validated['type'],
        ]);

        switch ($user->type) {
            case 0: // Customer
                Customer::create([
                    'user_id' => $user->id,
                    'is_company' => $validated['is_company'],
                    'fname' => $validated['fname'],
                    'mname' => $validated['mname'],
                    'lname' => $validated['lname'],
                    'license_number' => $validated['license_number'],
                    'wereda' => $validated['wereda'],
                    'phone' => $validated['phone'],
                    'house_no' => $validated['house_no'],
                ]);
                break;
            case 1: // Agent
                Agent::create([
                    'user_id' => $user->id,
                    'fname' => $validated['fname'],
                    'mname' => $validated['mname'],
                    'lname' => $validated['lname'],
                    'phone' => $validated['phone'],
                ]);
                break;
            case 2: // Admin
                Admin::create([
                    'user_id' => $user->id,
                    'fname' => $validated['fname'],
                    'mname' => $validated['mname'],
                    'lname' => $validated['lname'],
                    'phone' => $validated['phone'],
                ]);
                break;
        }

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'email' => 'sometimes|required|string|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:6',
            'type' => 'sometimes|required|in:0,1,2',
            'fname' => 'sometimes|required|string',
            'mname' => 'nullable|string',
            'lname' => 'sometimes|required|string',
            'phone' => 'sometimes|required|string',
            'is_company' => 'required_if:type,0|boolean',
            'license_number' => 'required_if:type,0|string',
            'wereda' => 'required_if:type,0|string',
            'house_no' => 'required_if:type,0|string',
        ]);

        if ($request->has('password')) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        // Update related models based on type
        switch ($user->type) {
            case 0: // Customer
                $user->customer->update($validated);
                break;
            case 1: // Agent
                $user->agent->update($validated);
                break;
            case 2: // Admin
                $user->admin->update($validated);
                break;
        }

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        switch($user->type){
            case 0: // Customer
                $user->customers->delete();
                break;
            case 1: // Agent
                $user->agent->delete();
                break;
            case 2: // Admin
                $user->admin->delete();
                break;
        }
        
        $user->delete();

        return response()->noContent();
    }

    public function getNotifications(User $user) {
        return NotificationResource::collection($user->notifications);
    }

    public function markAsRead(Notification $notification) {
        $notification->update(['read' => true]);

        return new NotificationResource($notification);
    }
}
