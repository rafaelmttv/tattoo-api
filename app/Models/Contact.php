<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['contactable_type', 'contactable_id', 'type', 'value'];

    public function contactable()
    {
        return $this->morphTo();
    }
}