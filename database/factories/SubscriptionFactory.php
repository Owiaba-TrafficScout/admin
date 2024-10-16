<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plan_id' => fake()->numberBetween(1, 3),
            'tenant_id' => fake()->unique()->numberBetween(1, 10),
            'trial_ends_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'start_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => fake()->dateTimeBetween('+1 month', '+2 month'),
            'status_id' => fake()->numberBetween(1, 3),
        ];
    }
}
