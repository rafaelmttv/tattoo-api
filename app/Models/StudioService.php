<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudioService extends Model
{
    use HasFactory, SoftDeletes;

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
        return $this->belongsTo(Studio::class, 'provider_id');
    }
}