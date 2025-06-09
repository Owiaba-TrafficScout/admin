<?php

namespace Database\Factories;

use App\Models\CarType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'car_type_id' => CarType::inRandomOrder()->first()->id,
            'car_number' => $this->faker->buildingNumber(),
        ];
    }
}
