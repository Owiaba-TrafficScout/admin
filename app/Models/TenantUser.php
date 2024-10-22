<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TenantUser extends Pivot
{
    /** @use HasFactory<\Database\Factories\TenantUserFactory> */
    use HasFactory;

    protected $table = 'tenant_user';
    protected $guarded = [];

    /**
     * Get the tenant that the user belongs to.
     */

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the user that belongs to the tenant.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role that the user has for the tenant.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(TenantRole::class, 'tenant_role_id');
    }

    /**
     * ditermin is admin in any projecg
     */
    public function isAdmin(): bool
    {
        return $this->role->name === 'admin';
    }
}
