<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    /** @use HasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all subscriptions for the tenant.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the current active subscription for the tenant.
     */
    public function currentSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->where('status_id', 1);
    }

    /**
     * check if the tenant has an active subscription
     */
    public function hasActiveSubscription(): bool
    {
        return $this->currentSubscription()->exists();
    }

    /**
     * Get all projects for the tenant.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get all users for the tenant.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(TenantUser::class)
            ->withPivot(['tenant_role_id', 'id'])
            ->withTimestamps();
    }

    /**
     * Get all tenant users who are admins
     */

    public function admins(): BelongsToMany
    {
        return $this->users()->wherePivot('tenant_role_id', env('TENANT_ADMIN_ROLE_ID'));
    }
}
