<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TenantUser>
 */
class TenantUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'tenant_role_id' => TenantRole::inRandomOrder()->first()->id,
        ];
    }
}
