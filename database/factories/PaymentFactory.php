<?php

namespace Database\Factories;

use App\Models\PaymentStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'project_id' => Project::inRandomOrder()->first()->id,
            'payment_status_id' => PaymentStatus::inRandomOrder()->first()->id,
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence,
        ];
    }
}
