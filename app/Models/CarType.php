<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarType extends Model
{
    /** @use HasFactory<\Database\Factories\CarTypeFactory> */
    use HasFactory;
    protected $guarded = [];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    /**
     * Get all projects this car type is associated with.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_car_type');
    }
}
