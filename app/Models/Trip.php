<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasFactory, SoftDeletes;

    protected $with = ['car', 'speeds', 'stops', 'projectUser'];

    protected $guarded = [];

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

    /**
     * Get the tenant that the trip belongs to.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
