<?php

use App\Http\Controllers\ActorController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Actor routes - make actors index the home page
Route::get('/', [ActorController::class, 'index'])->name('home');
Route::get('/create', [ActorController::class, 'create'])->name('actors.create');
Route::post('/actors', [ActorController::class, 'store'])->name('actors.store');
