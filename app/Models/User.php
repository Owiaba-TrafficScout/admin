<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, EagerLoadPivotTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be appended to the model's array and JSON form.
     *
     * @var array
     */



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }





    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }


    /**
     * The projects that the user belongs to.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')
            ->using(ProjectUser::class)
            ->withPivot(['id', 'role_id', 'joined_at'])
            ->withTimestamps();
    }

    /**
     * The projects where the user is an admin.
     */
    public function adminProjects()
    {
        return $this->belongsToMany(Project::class)
            ->using(ProjectUser::class)
            ->withPivot(['id', 'role_id', 'joined_at'])
            ->withTimestamps()
            ->wherePivot('role_id', Role::where('name', 'admin')->first()->id);
    }

    /**
     * Check if the user is an admin in any project.
     */
    public function isAdminInAnyProject()
    {
        return $this->adminProjects()->exists();
    }


    /**
     * The tenants that the user belongs to.
     */
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class)
            ->using(TenantUser::class)
            ->withPivot(['id', 'tenant_role_id'])
            ->withTimestamps();
    }

    /**
     * The tenants where the user is an admin.
     */
    public function tenantsWhereAdmin(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class)
            ->using(TenantUser::class)
            ->withPivot(['id', 'tenant_role_id'])
            ->withTimestamps()
            ->wherePivot('tenant_role_id', env('TENANT_ADMIN_ROLE_ID'));
    }

    /**
     * Check if the user is an admin in any tenant.
     */
    public function isAdminInAnyTenant(): bool
    {
        return $this->tenantsWhereAdmin()->exists();
    }

    /**
     * Check if the user is an admin in the given tenant.
     */
    public function isAdminInTenant($tenant_id = null): bool
    {
        if (is_null($tenant_id)) {
            $tenant_id = session('tenant_id');
        }
        return $this->tenantsWhereAdmin()->where('tenant_id', $tenant_id)->exists();
    }

    /**
     * Get all of the trips for the user.
     */

    public function trips(): HasManyThrough
    {
        return $this->hasManyThrough(Trip::class, ProjectUser::class, 'user_id', 'project_user_id', 'id', 'id');
    }

    /**
     * Get all trips for the user in projects where the user is an admin.
     */
    public function adminTrips(): Collection
    {
        return $this->adminProjects()->with('trips')->get()->pluck('trips')->flatten();
    }
}
