<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TripSpeed extends Model
{
    /** @use HasFactory<\Database\Factories\TripSpeedFactory> */
    use HasFactory;

    protected $guarded = [];

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}