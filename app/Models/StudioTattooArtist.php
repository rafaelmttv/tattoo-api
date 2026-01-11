<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioTattooArtist extends Model
{
    use HasFactory;

    protected $fillable = ['studio_id', 'tattoo_artist_id'];

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function tattooArtist()
    {
        return $this->belongsTo(TattooArtist::class);
    }
}