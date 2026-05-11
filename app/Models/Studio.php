<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Studio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'address',
        'city',
        'state',
        'description',
        'logo_url',
        'cover_url',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];

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