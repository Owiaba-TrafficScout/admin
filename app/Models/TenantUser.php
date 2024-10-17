<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TenantUser extends Pivot
{
    /** @use HasFactory<\Database\Factories\TenantUserFactory> */
    use HasFactory;

    protected $table = 'tenant_user';
    protected $guarded = [];
}
