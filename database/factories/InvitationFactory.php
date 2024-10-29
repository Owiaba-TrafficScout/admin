<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'role_id' => Role::all()->random()->id,
            'project_id' => Project::all()->random()->id,
            'accepted' => $this->faker->boolean,
            'token' => $this->faker->sha256,
            'expires_at' => $this->faker->dateTime,
        ];
    }
}
