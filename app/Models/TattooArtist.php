<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TattooArtist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'slug',
        'bio',
        'experience_years',
        'specialties',
        'avatar_url',
    ];

    protected $casts = [
        'specialties' => 'array',
        'experience_years' => 'integer',
    ];

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