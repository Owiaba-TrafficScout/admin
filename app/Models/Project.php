<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['expired'];
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($project) {
            // Ensure the tenant relationship is loaded
            $tenant = Tenant::find($project->tenant_id);

            if (!$tenant || !$tenant->hasActiveSubscription()) {
                throw new \Exception('Cannot create a project without an active subscription.');
            }
        });
    }

    /**
     * Determine if the project is expired.
     */
    public function getExpiredAttribute(): bool
    {
        // Only expired if end_date is set and the current date is greater
        return $this->end_date && Carbon::now()->greaterThan($this->end_date);
    }


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get tenant project belongs to.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * The users that belong to the project.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->using(ProjectUser::class)
            ->withPivot(['id', 'role_id', 'joined_at'])
            ->withTimestamps();
    }

    /**
     * Get all admins.
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->using(ProjectUser::class)
            ->withPivot(['id', 'role_id', 'joined_at'])
            ->withTimestamps()
            ->wherePivot('role_id', Role::where('name', 'admin')->first()->id);
    }

    /**
     * Get all trips for the project.
     */
    public function trips(): HasManyThrough
    {
        return $this->hasManyThrough(Trip::class, ProjectUser::class, 'project_id', 'project_user_id');
    }

    /**
     * Get all CarTypes for project
     */
    public function carTypes(): BelongsToMany
    {
        return $this->belongsToMany(CarType::class, 'project_car_type');
    }

    /**
     * Get all invitations for the project.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Get current state
     */
    public function state(): HasOne
    {
        return $this->hasOne(State::class);
    }
}
