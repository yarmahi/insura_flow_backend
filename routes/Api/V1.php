<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PlanTypeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;


// User Route
Route::apiResource('users', UserController::class);

// Agent Route
Route::prefix('agents')->group(function (){
    Route::get('/', [AgentController::class, 'index']);
    Route::get('{agent}', [AgentController::class, 'show']);
});

// Claim Route
Route::apiResource('claims', ClaimController::class);
Route::prefix('claims')->group(function () {
    Route::get('/', [ClaimController::class, 'index']);
    Route::get('{claims}', [AgentController::class, 'show']);
    Route::post('{claim}/link-agent', [ClaimController::class, 'linkAgent']);
    Route::post('{claim}/unlink-agent', [ClaimController::class, 'unlinkAgent']);
    Route::post('{claim}/status', [ClaimController::class, 'changeStatus']);
});

// customer Route
Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::get('{customer}', [CustomerController::class, 'show']);
    Route::post('{customer}/link-agent', [CustomerController::class, 'linkAgent']);
    Route::post('{customer}/unlink-agent', [CustomerController::class, 'unlinkAgent']);
});

// Plan Type Route
Route::apiResource('plan-types', PlanTypeController::class);

// Vehicle Route
Route::apiResource('vehicles', VehicleController::class);
Route::prefix('vehicles')->group(function () {
    Route::post('{vehicle}/link-plan-type', [VehicleController::class, 'linkPlanType']);
    Route::post('{vehicle}/unlink-plan-type', [VehicleController::class, 'unlinkPlanType']);
});
