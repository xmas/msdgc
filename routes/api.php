<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SponsorController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Sponsor API routes
Route::get('/sponsors', [SponsorController::class, 'index']);
Route::get('/sponsors/{id}', [SponsorController::class, 'show']);
