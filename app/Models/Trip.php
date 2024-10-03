<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip extends Model
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasFactory;

    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo(TripStatus::class);
    }

    public function speed(): BelongsTo
    {
        return $this->belongsTo(TripSpeed::class);
    }
}
