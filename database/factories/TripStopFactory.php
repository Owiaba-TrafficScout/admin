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
            'start_time' => $this->faker->dateTime(),
            'start_location_x' => $this->faker->randomFloat(2, 0, 100),
            'start_location_y' => $this->faker->randomFloat(2, 0, 100),
            'stop_time' => $this->faker->dateTime(),
            'stop_location_x' => $this->faker->randomFloat(2, 0, 100),
            'stop_location_y' => $this->faker->randomFloat(2, 0, 100),
            'passengers_count' => random_int(1, 15),
            'passengers_boarding' => random_int(1, 15),
            'passengers_alighting' => random_int(1, 15),
            'is_traffic' => random_int(0,1),
            // 'description' => $this->faker->sentence(),
        ];
    }
}
