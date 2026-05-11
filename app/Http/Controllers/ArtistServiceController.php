<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtistServiceRequest;
use App\Http\Requests\UpdateArtistServiceRequest;
use App\Http\Resources\ArtistServiceResource;
use App\Services\ArtistServiceService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArtistServiceController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ArtistServiceService $artistServiceService
    ) {}

    /**
     * List active artist services with pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $services = $this->artistServiceService->list(
            perPage: $request->integer('per_page', 15)
        );

        return ArtistServiceResource::collection($services);
    }

    /**
     * Show a single artist service.
     */
    public function show(int $id): ArtistServiceResource
    {
        $service = $this->artistServiceService->find($id);

        return new ArtistServiceResource($service);
    }

    /**
     * Create a new artist service.
     */
    public function store(StoreArtistServiceRequest $request): JsonResponse
    {
        $service = $this->artistServiceService->create(
            $request->user()->tattooArtist,
            $request->validated()
        );

        return $this->createdResponse(new ArtistServiceResource($service));
    }

    /**
     * Update an existing artist service.
     */
    public function update(UpdateArtistServiceRequest $request, int $id): ArtistServiceResource
    {
        $service = $this->artistServiceService->find($id);

        $this->authorize('update', $service);

        $service = $this->artistServiceService->update($service, $request->validated());

        return new ArtistServiceResource($service);
    }
}