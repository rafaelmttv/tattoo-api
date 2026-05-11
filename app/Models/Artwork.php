<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artwork extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'image_url',
        'body_location',
        'style',
        'tags',
        'price',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
        'tags' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(TattooArtist::class, 'creator_id');
    }
}