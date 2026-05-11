<?php

namespace App\Services;

use App\Models\Studio;
use App\Models\StudioService as StudioServiceModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StudioServiceService
{
    /**
     * List active studio services with pagination.
     */
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return StudioServiceModel::with('provider.user')
            ->where('active', true)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find a studio service by ID.
     */
    public function find(int $id): StudioServiceModel
    {
        return StudioServiceModel::with('provider.user')->findOrFail($id);
    }

    /**
     * Create a new studio service for the given studio.
     *
     * @param array<string, mixed> $data
     */
    public function create(Studio $studio, array $data): StudioServiceModel
    {
        return StudioServiceModel::create([
            'provider_id' => $studio->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'duration' => $data['duration'] ?? null,
            'active' => $data['active'] ?? true,
        ]);
    }

    /**
     * Update an existing studio service.
     *
     * @param array<string, mixed> $data
     */
    public function update(StudioServiceModel $service, array $data): StudioServiceModel
    {
        $service->update($data);

        return $service->fresh(['provider.user']);
    }
}
