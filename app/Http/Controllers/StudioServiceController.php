<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudioServiceRequest;
use App\Http\Requests\UpdateStudioServiceRequest;
use App\Http\Resources\StudioServiceResource;
use App\Services\StudioServiceService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudioServiceController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly StudioServiceService $studioServiceService
    ) {}

    /**
     * List active studio services with pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $services = $this->studioServiceService->list(
            perPage: $request->integer('per_page', 15)
        );

        return StudioServiceResource::collection($services);
    }

    /**
     * Show a single studio service.
     */
    public function show(int $id): StudioServiceResource
    {
        $service = $this->studioServiceService->find($id);

        return new StudioServiceResource($service);
    }

    /**
     * Create a new studio service.
     */
    public function store(StoreStudioServiceRequest $request): JsonResponse
    {
        $service = $this->studioServiceService->create(
            $request->user()->studio,
            $request->validated()
        );

        return $this->createdResponse(new StudioServiceResource($service));
    }

    /**
     * Update an existing studio service.
     */
    public function update(UpdateStudioServiceRequest $request, int $id): StudioServiceResource
    {
        $service = $this->studioServiceService->find($id);

        $this->authorize('update', $service);

        $service = $this->studioServiceService->update($service, $request->validated());

        return new StudioServiceResource($service);
    }
}