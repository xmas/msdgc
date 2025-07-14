<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CMSCollectionsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
