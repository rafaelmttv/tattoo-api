<?php

namespace App\Policies;

use App\Models\ArtistService;
use App\Models\User;

class ArtistServicePolicy
{
    /**
     * Anyone can view artist services.
     */
    public function view(?User $user, ArtistService $service): bool
    {
        return true;
    }

    /**
     * Only users with a tattoo artist profile can create services.
     */
    public function create(User $user): bool
    {
        return $user->tattooArtist !== null;
    }

    /**
     * Only the service provider can update their services.
     */
    public function update(User $user, ArtistService $service): bool
    {
        return $user->tattooArtist
            && $user->tattooArtist->id === $service->provider_id;
    }

    /**
     * Only the service provider can delete their services.
     */
    public function delete(User $user, ArtistService $service): bool
    {
        return $user->tattooArtist
            && $user->tattooArtist->id === $service->provider_id;
    }
}
