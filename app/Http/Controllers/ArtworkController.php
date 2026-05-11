<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtworkRequest;
use App\Http\Requests\UpdateArtworkRequest;
use App\Http\Resources\ArtworkResource;
use App\Services\ArtworkService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArtworkController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ArtworkService $artworkService
    ) {}

    /**
     * List active artworks with optional filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $artworks = $this->artworkService->list(
            filters: $request->only(['body_location', 'creator_id']),
            perPage: $request->integer('per_page', 15)
        );

        return ArtworkResource::collection($artworks);
    }

    /**
     * Show a single artwork.
     */
    public function show(int $id): ArtworkResource
    {
        $artwork = $this->artworkService->find($id);

        return new ArtworkResource($artwork);
    }

    /**
     * Create a new artwork.
     */
    public function store(StoreArtworkRequest $request): JsonResponse
    {
        $artwork = $this->artworkService->create(
            $request->user()->tattooArtist,
            $request->validated()
        );

        return $this->createdResponse(new ArtworkResource($artwork));
    }

    /**
     * Update an existing artwork.
     */
    public function update(UpdateArtworkRequest $request, int $id): ArtworkResource
    {
        $artwork = $this->artworkService->find($id);

        $this->authorize('update', $artwork);

        $artwork = $this->artworkService->update($artwork, $request->validated());

        return new ArtworkResource($artwork);
    }
}