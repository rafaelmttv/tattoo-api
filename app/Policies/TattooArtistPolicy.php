<?php

namespace App\Policies;

use App\Models\TattooArtist;
use App\Models\User;

class TattooArtistPolicy
{
    /**
     * Anyone can view tattoo artist profiles.
     */
    public function view(?User $user, TattooArtist $artist): bool
    {
        return true;
    }

    /**
     * Any authenticated user can create a tattoo artist profile.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the profile owner can update it.
     */
    public function update(User $user, TattooArtist $artist): bool
    {
        return $user->id === $artist->user_id;
    }

    /**
     * Only the profile owner can delete it.
     */
    public function delete(User $user, TattooArtist $artist): bool
    {
        return $user->id === $artist->user_id;
    }
}
