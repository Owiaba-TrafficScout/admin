<?php

namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TripStop>
 */
class TripStopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trip_id' => random_int(1, 10),
            'location_x' => $this->faker->randomFloat(2, 0, 100),
            'location_y' => $this->faker->randomFloat(2, 0, 100),
            'stop_time' => $this->faker->dateTime(),
            'description' => $this->faker->sentence(),
        ];
    }
}
