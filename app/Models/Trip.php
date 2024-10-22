<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Trip extends Model
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasFactory;

    protected $with = ['status', 'user', 'car', 'speeds', 'stops'];

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
        return $this->belongsTo(ProjectUser::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Get the project user undertaking this trip
     */

    public function projectUser(): BelongsTo
    {
        return $this->belongsTo(ProjectUser::class, 'project_user_id');
    }

    /**
     * Get the project that the trip belongs to.
     */
    public function project(): HasOneThrough
    {
        return $this->hasOneThrough(Project::class, ProjectUser::class, 'id', 'id', 'project_user_id', 'project_id');
    }
}
