<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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

    public function licenses(): BelongsToMany
    {
        return $this->belongsToMany(License::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isSystemAdmin(): bool
    {
        //convert role name to lowercase and compare
        return Str::lower($this->role->name) === 'system admin';
    }

    public function isProjectAdmin(): bool
    {
        //convert role name to lowercase and compare
        return Str::lower($this->role->name) === 'project admin';
    }

    public function isAdmin(): bool
    {
        return $this->isSystemAdmin() || $this->isProjectAdmin();
    }

    /**
     * The projects that the user belongs to.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_users')
            ->using(ProjectUser::class)
            ->withPivot(['id', 'role_id', 'joined_at'])
            ->withTimestamps();
    }
}
