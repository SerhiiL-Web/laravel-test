<?php

use App\Http\Controllers\ActorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Actor API routes
Route::get('/actors/prompt-validation', [ActorController::class, 'promptValidation']);
