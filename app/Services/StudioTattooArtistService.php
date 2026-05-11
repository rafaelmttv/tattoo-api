<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Models\Studio;
use App\Models\TattooArtist;
use Illuminate\Database\Eloquent\Collection;

class StudioTattooArtistService
{
    /**
     * List all tattoo artists associated with a studio.
     */
    public function listForStudio(int $studioId): Collection
    {
        $studio = Studio::findOrFail($studioId);

        return $studio->tattooArtists()->with('user')->get();
    }

    /**
     * Associate a tattoo artist with a studio.
     *
     * @throws BusinessException
     */
    public function associate(Studio $studio, int $tattooArtistId): void
    {
        $tattooArtist = TattooArtist::findOrFail($tattooArtistId);

        if ($studio->tattooArtists()->where('tattoo_artist_id', $tattooArtist->id)->exists()) {
            throw new BusinessException('This tattoo artist is already associated with this studio.');
        }

        $studio->tattooArtists()->attach($tattooArtist);
    }

    /**
     * Disassociate a tattoo artist from a studio.
     */
    public function disassociate(Studio $studio, int $tattooArtistId): void
    {
        $tattooArtist = TattooArtist::findOrFail($tattooArtistId);

        $studio->tattooArtists()->detach($tattooArtist);
    }
}
