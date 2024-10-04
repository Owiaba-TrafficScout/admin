<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasFactory;

    protected $with = ['status', 'user', 'car', 'project'];

    protected $guarded = [];

    public function status(): BelongsTo
    {
        return $this->belongsTo(TripStatus::class, 'trip_status_id');
    }

    public function speeds(): HasMany
    {
        return $this->hasMany(TripSpeed::class);
    }

    public function stops(): HasMany
    {
        return $this->hasMany(TripStop::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
