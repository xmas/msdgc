<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CMSCollectionsController;
use App\Http\Controllers\Api\MemberController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Member management API routes (using Sanctum with session authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('members', MemberController::class, ['parameters' => ['members' => 'member']]);
    Route::post('/members/bulk-import', [MemberController::class, 'bulkImport']);

    // Event management API routes
    Route::apiResource('events', App\Http\Controllers\Api\EventController::class);

    // Event-User relationship API routes
    Route::post('/events/{event}/users', [App\Http\Controllers\EventUserController::class, 'attach']);
    Route::delete('/events/{event}/users/{user}', [App\Http\Controllers\EventUserController::class, 'detach']);
    Route::put('/events/{event}/users/{user}/attributes', [App\Http\Controllers\EventUserController::class, 'updateAttributes']);
    Route::get('/events/{event}/users', [App\Http\Controllers\EventUserController::class, 'getUsers']);
    Route::get('/users/{user}/events', [App\Http\Controllers\EventUserController::class, 'getUserEvents']);
});

// CMS Collections API routes
Route::get('/collections/{collection}', [CMSCollectionsController::class, 'index']);
Route::get('/collections/{collection}/{id}', [CMSCollectionsController::class, 'show']);

// Backward compatibility routes (optional - can be removed later)
Route::get('/sponsors', function() {
    return app(CMSCollectionsController::class)->index('sponsors');
});
Route::get('/sponsors/{id}', function(string $id) {
    return app(CMSCollectionsController::class)->show('sponsors', $id);
});
