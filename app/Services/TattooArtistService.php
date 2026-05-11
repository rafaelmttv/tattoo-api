<?php

namespace App\Services;

use App\Models\TattooArtist;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TattooArtistService
{
    /**
     * List tattoo artists with pagination.
     */
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return TattooArtist::with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find a tattoo artist by ID with relationships.
     */
    public function find(int $id): TattooArtist
    {
        return TattooArtist::with(['user', 'contacts', 'artworks', 'artistServices', 'studios.user'])
            ->findOrFail($id);
    }

    /**
     * Create a new tattoo artist profile for the given user.
     *
     * @param array<string, mixed> $data
     */
    public function create(User $user, array $data): TattooArtist
    {
        return TattooArtist::create([
            'user_id' => $user->id,
            'bio' => $data['bio'] ?? null,
            'experience_years' => $data['experience_years'] ?? null,
        ]);
    }

    /**
     * Update an existing tattoo artist profile.
     *
     * @param array<string, mixed> $data
     */
    public function update(TattooArtist $artist, array $data): TattooArtist
    {
        $artist->update($data);

        return $artist->fresh(['user']);
    }

    /**
     * Delete a tattoo artist profile.
     */
    public function delete(TattooArtist $artist): void
    {
        $artist->delete();
    }
}
