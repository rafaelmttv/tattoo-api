<?php

namespace App\Policies;

use App\Models\Artwork;
use App\Models\User;

class ArtworkPolicy
{
    /**
     * Anyone can view artworks.
     */
    public function view(?User $user, Artwork $artwork): bool
    {
        return true;
    }

    /**
     * Any authenticated user with a tattoo artist profile can create artworks.
     */
    public function create(User $user): bool
    {
        return $user->tattooArtist !== null;
    }

    /**
     * Only the artwork creator can update it.
     */
    public function update(User $user, Artwork $artwork): bool
    {
        return $user->tattooArtist
            && $user->tattooArtist->id === $artwork->creator_id;
    }

    /**
     * Only the artwork creator can delete it.
     */
    public function delete(User $user, Artwork $artwork): bool
    {
        return $user->tattooArtist
            && $user->tattooArtist->id === $artwork->creator_id;
    }
}
