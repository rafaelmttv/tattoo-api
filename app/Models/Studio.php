<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'address', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function studioServices()
    {
        return $this->hasMany(StudioService::class, 'provider_id');
    }

    public function tattooArtists()
    {
        return $this->belongsToMany(TattooArtist::class, 'studio_tattoo_artists');
    }
}