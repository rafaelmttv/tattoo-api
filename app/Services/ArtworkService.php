<?php

namespace App\Services;

use App\Models\Artwork;
use App\Models\TattooArtist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArtworkService
{
    /**
     * List active artworks with optional filters and pagination.
     *
     * @param array<string, mixed> $filters
     */
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Artwork::with('creator.user')->where('active', true);

        if (!empty($filters['body_location'])) {
            $query->where('body_location', $filters['body_location']);
        }

        if (!empty($filters['creator_id'])) {
            $query->where('creator_id', $filters['creator_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find an artwork by ID with relationships.
     */
    public function find(int $id): Artwork
    {
        return Artwork::with('creator.user')->findOrFail($id);
    }

    /**
     * Create a new artwork for the given tattoo artist.
     *
     * @param array<string, mixed> $data
     */
    public function create(TattooArtist $artist, array $data): Artwork
    {
        return Artwork::create([
            'creator_id' => $artist->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'image_url' => $data['image_url'] ?? null,
            'body_location' => $data['body_location'] ?? null,
            'price' => $data['price'] ?? null,
            'active' => $data['active'] ?? true,
        ]);
    }

    /**
     * Update an existing artwork.
     *
     * @param array<string, mixed> $data
     */
    public function update(Artwork $artwork, array $data): Artwork
    {
        $artwork->update($data);

        return $artwork->fresh(['creator.user']);
    }
}
