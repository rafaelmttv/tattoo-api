<?php

namespace App\Services;

use App\Models\Artwork;
use App\Models\Studio;
use App\Models\TattooArtist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ShowcaseService
{
    /**
     * List studios for the public showcase with optional filters.
     *
     * @param array<string, mixed> $filters
     */
    public function studios(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Studio::with(['user', 'contacts'])
            ->withCount(['studioServices', 'tattooArtists']);

        if (!empty($filters['city'])) {
            $query->where('city', 'LIKE', "%{$filters['city']}%");
        }

        if (!empty($filters['state'])) {
            $query->where('state', $filters['state']);
        }

        if (!empty($filters['featured'])) {
            $query->where('featured', true);
        }

        if (!empty($filters['search'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['search']}%")
                    ->orWhere('description', 'LIKE', "%{$filters['search']}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Show a single studio with full showcase details.
     */
    public function showStudio(int $id): Studio
    {
        return Studio::with([
            'user',
            'contacts',
            'studioServices' => fn ($q) => $q->where('active', true),
            'tattooArtists.user',
            'tattooArtists.artworks' => fn ($q) => $q->where('active', true)->latest()->limit(4),
        ])
            ->withCount(['studioServices', 'tattooArtists'])
            ->findOrFail($id);
    }

    /**
     * Get the portfolio (artworks) of a studio's artists.
     */
    public function studioPortfolio(int $studioId, int $perPage = 15): LengthAwarePaginator
    {
        $studio = Studio::findOrFail($studioId);
        $artistIds = $studio->tattooArtists()->pluck('tattoo_artists.id');

        return Artwork::with('creator.user')
            ->whereIn('creator_id', $artistIds)
            ->where('active', true)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * List tattoo artists for the public showcase with optional filters.
     *
     * @param array<string, mixed> $filters
     */
    public function artists(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = TattooArtist::with(['user', 'contacts'])
            ->withCount(['artworks', 'artistServices']);

        if (!empty($filters['search'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('bio', 'LIKE', "%{$filters['search']}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'LIKE', "%{$filters['search']}%"));
            });
        }

        if (!empty($filters['specialty'])) {
            $query->whereJsonContains('specialties', $filters['specialty']);
        }

        if (!empty($filters['min_experience'])) {
            $query->where('experience_years', '>=', (int) $filters['min_experience']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Show a single tattoo artist with full showcase details.
     */
    public function showArtist(int $id): TattooArtist
    {
        return TattooArtist::with([
            'user',
            'contacts',
            'artistServices' => fn ($q) => $q->where('active', true),
            'studios.user',
            'artworks' => fn ($q) => $q->where('active', true)->latest(),
        ])
            ->withCount(['artworks', 'artistServices', 'studios'])
            ->findOrFail($id);
    }

    /**
     * Get the portfolio (artworks) for a specific artist.
     */
    public function artistPortfolio(int $artistId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Artwork::with('creator.user')
            ->where('creator_id', $artistId)
            ->where('active', true);

        if (!empty($filters['style'])) {
            $query->where('style', $filters['style']);
        }

        if (!empty($filters['body_location'])) {
            $query->where('body_location', $filters['body_location']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * List artworks for the public showcase with optional filters.
     *
     * @param array<string, mixed> $filters
     */
    public function artworks(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Artwork::with('creator.user')
            ->where('active', true);

        if (!empty($filters['style'])) {
            $query->where('style', $filters['style']);
        }

        if (!empty($filters['body_location'])) {
            $query->where('body_location', $filters['body_location']);
        }

        if (!empty($filters['search'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['search']}%")
                    ->orWhere('description', 'LIKE', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Show a single artwork for the showcase.
     */
    public function showArtwork(int $id): Artwork
    {
        return Artwork::with([
            'creator.user',
            'creator.studios.user',
        ])->findOrFail($id);
    }

    /**
     * Global search across studios, artists, and artworks.
     *
     * @return array<string, mixed>
     */
    public function search(string $term, int $limit = 5): array
    {
        $studios = Studio::with('user')
            ->where('name', 'LIKE', "%{$term}%")
            ->orWhere('description', 'LIKE', "%{$term}%")
            ->limit($limit)
            ->get();

        $artists = TattooArtist::with('user')
            ->whereHas('user', fn ($q) => $q->where('name', 'LIKE', "%{$term}%"))
            ->orWhere('bio', 'LIKE', "%{$term}%")
            ->limit($limit)
            ->get();

        $artworks = Artwork::with('creator.user')
            ->where('active', true)
            ->where(function (Builder $q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('description', 'LIKE', "%{$term}%");
            })
            ->limit($limit)
            ->get();

        return [
            'studios' => $studios,
            'artists' => $artists,
            'artworks' => $artworks,
        ];
    }
}
