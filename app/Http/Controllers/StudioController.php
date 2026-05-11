<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudioRequest;
use App\Http\Requests\UpdateStudioRequest;
use App\Http\Resources\StudioResource;
use App\Services\StudioService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudioController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly StudioService $studioService
    ) {}

    /**
     * List all studios with pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $studios = $this->studioService->list(
            perPage: $request->integer('per_page', 15)
        );

        return StudioResource::collection($studios);
    }

    /**
     * Show a single studio with its relationships.
     */
    public function show(int $id): StudioResource
    {
        $studio = $this->studioService->find($id);

        return new StudioResource($studio);
    }

    /**
     * Create a new studio.
     */
    public function store(StoreStudioRequest $request): JsonResponse
    {
        $studio = $this->studioService->create(
            $request->user(),
            $request->validated()
        );

        return $this->createdResponse(new StudioResource($studio));
    }

    /**
     * Update an existing studio.
     */
    public function update(UpdateStudioRequest $request, int $id): StudioResource
    {
        $studio = $this->studioService->find($id);

        $this->authorize('update', $studio);

        $studio = $this->studioService->update($studio, $request->validated());

        return new StudioResource($studio);
    }

    /**
     * Delete a studio.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $studio = $this->studioService->find($id);

        $this->authorize('delete', $studio);

        $this->studioService->delete($studio);

        return $this->noContentResponse();
    }
}