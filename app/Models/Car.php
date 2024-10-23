<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\CarFactory> */
    use HasFactory;
    protected $guarded = [];

    protected $with = ['type'];


    public function type(): BelongsTo
    {
        return $this->belongsTo(CarType::class, 'car_type_id');
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
