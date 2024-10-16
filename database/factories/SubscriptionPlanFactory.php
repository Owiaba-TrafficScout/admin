<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionPlan>
 */
class SubscriptionPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subscriptionPlans = ['Basic', 'Pro', 'Enterprise'];
        return [
            'name' => fake()->unique()->randomElement($subscriptionPlans),
            'slug' => $this->faker->unique()->slug,
            'price' => $this->faker->randomFloat(4, 0, 9999),
            'max_projects' => $this->faker->randomNumber(),
            'max_users_per_project' => $this->faker->randomNumber(),
            'features' => json_encode(['feature1', 'feature2', 'feature3']),
        ];
    }
}
