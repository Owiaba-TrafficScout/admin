<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarStatus extends Model
{
    /** @use HasFactory<\Database\Factories\CarStatusFactory> */
    use HasFactory;

    protected $guarded = [];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
