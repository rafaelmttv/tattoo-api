<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTattooArtistRequest;
use App\Http\Requests\UpdateTattooArtistRequest;
use App\Http\Resources\TattooArtistResource;
use App\Services\TattooArtistService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TattooArtistController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly TattooArtistService $tattooArtistService
    ) {}

    /**
     * List all tattoo artists with pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $artists = $this->tattooArtistService->list(
            perPage: $request->integer('per_page', 15)
        );

        return TattooArtistResource::collection($artists);
    }

    /**
     * Show a single tattoo artist with relationships.
     */
    public function show(int $id): TattooArtistResource
    {
        $artist = $this->tattooArtistService->find($id);

        return new TattooArtistResource($artist);
    }

    /**
     * Create a new tattoo artist profile.
     */
    public function store(StoreTattooArtistRequest $request): JsonResponse
    {
        $artist = $this->tattooArtistService->create(
            $request->user(),
            $request->validated()
        );

        return $this->createdResponse(new TattooArtistResource($artist));
    }

    /**
     * Update an existing tattoo artist profile.
     */
    public function update(UpdateTattooArtistRequest $request, int $id): TattooArtistResource
    {
        $artist = $this->tattooArtistService->find($id);

        $this->authorize('update', $artist);

        $artist = $this->tattooArtistService->update($artist, $request->validated());

        return new TattooArtistResource($artist);
    }

    /**
     * Delete a tattoo artist profile.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $artist = $this->tattooArtistService->find($id);

        $this->authorize('delete', $artist);

        $this->tattooArtistService->delete($artist);

        return $this->noContentResponse();
    }
}