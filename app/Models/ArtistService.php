<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistService extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'name',
        'description',
        'price',
        'duration',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'active' => 'boolean',
    ];

    public function provider()
    {
        return $this->belongsTo(TattooArtist::class, 'provider_id');
    }
}