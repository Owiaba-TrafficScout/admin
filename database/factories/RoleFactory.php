<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = ["system admin", 'project admin', 'enumerator'];
        //inside pick first unique element from $roles
        return [
            'name' => $this->faker->unique()->randomElement($roles),
        ];
    }
}
