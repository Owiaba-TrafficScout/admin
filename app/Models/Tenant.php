<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     * Get all projects for the tenant.
     */
    public function projects(): HasManyThrough
    {
        return $this->through('subscriptions')->has('projects');
    }

    /**
     * Get all users for the tenant.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
