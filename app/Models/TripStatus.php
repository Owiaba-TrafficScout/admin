<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TripStatus extends Model
{
    /** @use HasFactory<\Database\Factories\TripStatusFactory> */
    use HasFactory;

    protected $guarded = [];

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
