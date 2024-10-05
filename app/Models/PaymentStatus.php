<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentStatus extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentStatusFactory> */
    use HasFactory;
    protected $guarded = [];

    // public function payments(): HasMany
    // {
    //     return $this->hasMany(Payment::class);
    // }
}
