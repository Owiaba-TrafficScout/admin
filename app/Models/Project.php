<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Log::info('Project model booted');
        static::creating(function ($project) {
            // Ensure the tenant relationship is loaded
            Log::info($project);
            $tenant = Tenant::find($project->tenant_id);

            if (!$tenant || !$tenant->hasActiveSubscription()) {
                throw new \Exception('Cannot create a project without an active subscription.');
            }
        });
    }


    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
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
     * Get all project admins.
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->using(ProjectUser::class)
            ->withPivot(['id', 'role_id', 'joined_at'])
            ->withTimestamps()
            ->wherePivot('role_id', Role::where('name', 'project admin')->first()->id);
    }
}
