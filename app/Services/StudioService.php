<?php

namespace App\Services;

use App\Models\Studio;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StudioService
{
    /**
     * List studios with pagination.
     */
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Studio::with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find a studio by ID with relationships.
     */
    public function find(int $id): Studio
    {
        return Studio::with(['user', 'contacts', 'studioServices', 'tattooArtists.user'])
            ->findOrFail($id);
    }

    /**
     * Create a new studio for the given user.
     *
     * @param array<string, mixed> $data
     */
    public function create(User $user, array $data): Studio
    {
        return Studio::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'address' => $data['address'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
    }

    /**
     * Update an existing studio.
     *
     * @param array<string, mixed> $data
     */
    public function update(Studio $studio, array $data): Studio
    {
        $studio->update($data);

        return $studio->fresh(['user']);
    }

    /**
     * Delete a studio.
     */
    public function delete(Studio $studio): void
    {
        $studio->delete();
    }
}
