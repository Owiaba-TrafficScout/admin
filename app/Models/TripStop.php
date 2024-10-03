<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripStop extends Model
{
    /** @use HasFactory<\Database\Factories\TripStopFactory> */
    use HasFactory;

    protected $guarded = [];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
