<?php

namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TripSpeed>
 */
class TripSpeedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trip_id' => Trip::inRandomOrder()->first()->id,
            'time' => $this->faker->dateTime(),
            'location_x' => $this->faker->randomFloat(2, 0, 100),
            'location_y' => $this->faker->randomFloat(2, 0, 100),
            'velocity' => $this->faker->randomFloat(2, 0, 100),
            'is_traffic' => $this->faker->boolean(),
        ];
    }
}
