<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Tenant;
use App\Models\TripSpeed;
use App\Models\TripStop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'project_user_id' =>  ProjectUser::inRandomOrder()->first()->id,
            'tenant_id' => Tenant::inRandomOrder()->first()->id,
            'group_code' => $this->faker->word(),
            'car_id' => Car::inRandomOrder()->first()->id,
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
        ];
    }
}
