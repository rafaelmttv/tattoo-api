<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TattooArtist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'bio', 'experience_years'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function artworks()
    {
        return $this->hasMany(Artwork::class, 'creator_id');
    }

    public function artistServices()
    {
        return $this->hasMany(ArtistService::class, 'provider_id');
    }

    public function studios()
    {
        return $this->belongsToMany(Studio::class, 'studio_tattoo_artists');
    }
}