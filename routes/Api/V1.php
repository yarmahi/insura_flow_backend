<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


// User Route
Route::apiResource('users', UserController::class);

// Claim Route
Route::apiResource('claims', ClaimController::class);

// Agent Route
Route::prefix('agents')->group(function (){
    Route::get('/', [AgentController::class, 'index']);
    Route::get('{agent}', [AgentController::class, 'show']);
    Route::post('{agent}/link-claim', [AgentController::class, 'linkClaim']);
});

// Claim Route
Route::prefix('claims')->group(function () {
    Route::get('/', [ClaimController::class, 'index']);
    Route::post('{claim}/link-agent', [ClaimController::class, 'linkAgent']);
    Route::post('{claim}/unlink-agent', [ClaimController::class, 'unlinkAgent']);
});
