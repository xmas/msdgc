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
