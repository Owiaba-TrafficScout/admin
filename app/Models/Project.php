<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $guarded = [];

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
     * Get the tenant through the subscription.
     */
    public function tenant(): HasOneThrough
    {
        return $this->hasOneThrough(Tenant::class, Subscription::class, 'id', 'id', 'subscription_id', 'tenant_id');
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
