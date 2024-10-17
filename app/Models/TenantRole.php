<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantRole extends Model
{
    /** @use HasFactory<\Database\Factories\TenantRoleFactory> */
    use HasFactory;

    protected $guarded = [];
}
