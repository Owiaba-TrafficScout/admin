<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'code' => uniqid(),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'tenant_id' => Tenant::inRandomOrder()->first()->id,
        ];
    }

    public function withTenantId(int $tenantId): self
    {
        return $this->state([
            'tenant_id' => $tenantId,
        ]);
    }
}
