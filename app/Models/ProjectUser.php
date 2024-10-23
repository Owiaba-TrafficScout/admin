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
}
