<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PlanTypeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;

// Auth Route

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// User Route
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('{user}', [UserController::class, 'show']);
    Route::patch('{user}', [UserController::class, 'update']);
    Route::delete('{user}', [UserController::class, 'destroy']);
});

// Agent Route
Route::middleware(['auth:sanctum', 'user-access:admin'])->prefix('agents')->group(function (){
    Route::get('/', [AgentController::class, 'index']);
    Route::get('{agent}', [AgentController::class, 'show']);
});

// Claim Route
Route::middleware('auth:sanctum')->prefix('claims')->group(function () {
    Route::get('/', [ClaimController::class, 'index']);
    Route::post('/', [ClaimController::class, 'store']);
    Route::get('{claim}', [ClaimController::class, 'show']);
    Route::patch('{claim}', [ClaimController::class, 'update']);
    Route::delete('{claim}', [ClaimController::class, 'destroy']);
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
Route::prefix('plan-types')->group(function () {
    Route::get('/', [PlanTypeController::class, 'index']);
    Route::post('/', [PlanTypeController::class, 'store']);
    Route::get('{PlanType}', [PlanTypeController::class, 'show']);
    Route::patch('{PlanType}', [PlanTypeController::class, 'update']);
    Route::delete('{PlanType}', [PlanTypeController::class, 'destroy']);
});

// Vehicle Route
Route::prefix('vehicles')->group(function () {
    Route::get('/', [VehicleController::class, 'index']);
    Route::post('/', [VehicleController::class, 'store']);
    Route::get('{vehicle}', [VehicleController::class, 'show']);
    Route::patch('{vehicle}', [VehicleController::class, 'update']);
    Route::delete('{vehicle}', [VehicleController::class, 'destroy']);
    Route::post('{vehicle}/link-plan-type', [VehicleController::class, 'linkPlanType']);
    Route::post('{vehicle}/unlink-plan-type', [VehicleController::class, 'unlinkPlanType']);
});

// Invoice Route
Route::prefix('invoices')->group(function () {
    Route::get('/', [InvoiceController::class, 'index']);
    Route::post('/', [InvoiceController::class, 'store']);
    Route::get('{invoices}', [InvoiceController::class, 'show']);
    Route::patch('{invoices}', [InvoiceController::class, 'update']);
    Route::delete('{invoices}', [InvoiceController::class, 'destroy']);
    Route::post('{invoice}/status', [InvoiceController::class, 'updateStatus']);
});