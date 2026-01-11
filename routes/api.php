<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TattooArtistController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\ArtistServiceController;
use App\Http\Controllers\StudioServiceController;
use App\Http\Controllers\StudioTattooArtistController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');

Route::get('/roles', [RoleController::class, 'index']);
Route::get('/users/{id}/roles', [UserController::class, 'roles']);

Route::get('/artworks', [ArtworkController::class, 'index']);
Route::get('/artworks/{id}', [ArtworkController::class, 'show']);
Route::get('/artist-services', [ArtistServiceController::class, 'index']);
Route::get('/artist-services/{id}', [ArtistServiceController::class, 'show']);
Route::get('/studio-services', [StudioServiceController::class, 'index']);
Route::get('/studio-services/{id}', [StudioServiceController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/artworks', [ArtworkController::class, 'store']);
    Route::patch('/artworks/{id}', [ArtworkController::class, 'update']);
    Route::post('/artist-services', [ArtistServiceController::class, 'store']);
    Route::patch('/artist-services/{id}', [ArtistServiceController::class, 'update']);
    Route::post('/studio-services', [StudioServiceController::class, 'store']);
    Route::patch('/studio-services/{id}', [StudioServiceController::class, 'update']);
    Route::get('/studios/{studioId}/tattoo-artists', [StudioTattooArtistController::class, 'index']);
    Route::post('/studios/{studioId}/tattoo-artists', [StudioTattooArtistController::class, 'store']);
    Route::delete('/studios/{studioId}/tattoo-artists/{tattooArtistId}', [StudioTattooArtistController::class, 'destroy']);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('tattoo-artists', TattooArtistController::class);
    Route::apiResource('studios', StudioController::class);
    Route::apiResource('contacts', ContactController::class);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');