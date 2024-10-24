<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectUser extends Pivot
{
    /** @use HasFactory<\Database\Factories\ProjectUserFactory> */
    use HasFactory;

    protected $table = 'project_user';

    protected $guarded = [];
    protected $with = ['role', 'user', 'project'];
    public $incrementing = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($projectUser) {
            // Get the tenant ID from the project
            $tenantId = $projectUser->project->tenant_id;

            // Check if the user is already associated with the tenant
            if (!TenantUser::where('tenant_id', $tenantId)->where('user_id', $projectUser->user_id)->exists()) {
                // Attach the user to the tenant
                TenantUser::create([
                    'tenant_id' => $tenantId,
                    'user_id' => $projectUser->user_id,
                    'tenant_role_id' => 2, // Default role, change as needed
                ]);
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'project_user_id');
    }

    /**
     * Get the role associated with this project-user relationship.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if the user is an admin in the project.
     */
    public function isProjectAdmin(): bool
    {
        return $this->role->name === 'admin';
    }
}
