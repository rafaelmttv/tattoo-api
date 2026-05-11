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
use App\Http\Controllers\ShowcaseController;

/*
|--------------------------------------------------------------------------
| Public Auth Routes
|--------------------------------------------------------------------------
*/

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Public Showcase Routes (Vitrine)
|--------------------------------------------------------------------------
| These endpoints are publicly accessible without authentication.
| They provide read-only access to studios, artists, artworks, and services.
|--------------------------------------------------------------------------
*/

Route::prefix('showcase')->group(function () {
    // Studios
    Route::get('/studios', [ShowcaseController::class, 'studios']);
    Route::get('/studios/{id}', [ShowcaseController::class, 'showStudio']);
    Route::get('/studios/{id}/portfolio', [ShowcaseController::class, 'studioPortfolio']);

    // Tattoo Artists
    Route::get('/artists', [ShowcaseController::class, 'artists']);
    Route::get('/artists/{id}', [ShowcaseController::class, 'showArtist']);
    Route::get('/artists/{id}/portfolio', [ShowcaseController::class, 'artistPortfolio']);

    // Artworks
    Route::get('/artworks', [ShowcaseController::class, 'artworks']);
    Route::get('/artworks/{id}', [ShowcaseController::class, 'showArtwork']);

    // Global Search
    Route::get('/search', [ShowcaseController::class, 'search']);
});

/*
|--------------------------------------------------------------------------
| Public Reference Routes
|--------------------------------------------------------------------------
*/

Route::get('/roles', [RoleController::class, 'index']);
Route::get('/users/{id}/roles', [UserController::class, 'roles']);

/*
|--------------------------------------------------------------------------
| Public Read-Only Routes (Legacy — kept for backward compatibility)
|--------------------------------------------------------------------------
*/

Route::get('/artworks', [ArtworkController::class, 'index']);
Route::get('/artworks/{id}', [ArtworkController::class, 'show']);
Route::get('/artist-services', [ArtistServiceController::class, 'index']);
Route::get('/artist-services/{id}', [ArtistServiceController::class, 'show']);
Route::get('/studio-services', [StudioServiceController::class, 'index']);
Route::get('/studio-services/{id}', [StudioServiceController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::get('/user', fn (Request $request) => new \App\Http\Resources\UserResource($request->user()));

    // Artworks (create/update require TattooArtist profile)
    Route::post('/artworks', [ArtworkController::class, 'store']);
    Route::patch('/artworks/{id}', [ArtworkController::class, 'update']);

    // Artist Services
    Route::post('/artist-services', [ArtistServiceController::class, 'store']);
    Route::patch('/artist-services/{id}', [ArtistServiceController::class, 'update']);

    // Studio Services
    Route::post('/studio-services', [StudioServiceController::class, 'store']);
    Route::patch('/studio-services/{id}', [StudioServiceController::class, 'update']);

    // Studio ↔ TattooArtist associations
    Route::get('/studios/{studioId}/tattoo-artists', [StudioTattooArtistController::class, 'index']);
    Route::post('/studios/{studioId}/tattoo-artists', [StudioTattooArtistController::class, 'store']);
    Route::delete('/studios/{studioId}/tattoo-artists/{tattooArtistId}', [StudioTattooArtistController::class, 'destroy']);

    // Full CRUD Resources
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('tattoo-artists', TattooArtistController::class);
    Route::apiResource('studios', StudioController::class);
    Route::apiResource('contacts', ContactController::class);
});