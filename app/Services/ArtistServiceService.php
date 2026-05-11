<?php

namespace App\Services;

use App\Models\ArtistService;
use App\Models\TattooArtist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArtistServiceService
{
    /**
     * List active artist services with pagination.
     */
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return ArtistService::with('provider.user')
            ->where('active', true)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find an artist service by ID.
     */
    public function find(int $id): ArtistService
    {
        return ArtistService::with('provider.user')->findOrFail($id);
    }

    /**
     * Create a new artist service for the given tattoo artist.
     *
     * @param array<string, mixed> $data
     */
    public function create(TattooArtist $artist, array $data): ArtistService
    {
        return ArtistService::create([
            'provider_id' => $artist->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'duration' => $data['duration'] ?? null,
            'active' => $data['active'] ?? true,
        ]);
    }

    /**
     * Update an existing artist service.
     *
     * @param array<string, mixed> $data
     */
    public function update(ArtistService $service, array $data): ArtistService
    {
        $service->update($data);

        return $service->fresh(['provider.user']);
    }
}
