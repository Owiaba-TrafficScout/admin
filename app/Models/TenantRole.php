<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantRole extends Model
{
    /** @use HasFactory<\Database\Factories\TenantRoleFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all tenant users for the tenant role.
     */

    public function tenantUsers(): HasMany
    {
        return $this->hasMany(TenantUser::class);
    }
}
