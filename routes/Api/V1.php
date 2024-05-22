<?php 

use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


// User CRUD API
Route::apiResource('users', UserController::class);

// Claim CRUD API
Route::apiResource('claims', ClaimController::class);
