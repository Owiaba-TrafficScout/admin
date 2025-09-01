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
use Illuminate\Support\Carbon;

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
        // Ensure coherent times and linked tenant based on selected project_user
        $projectUser = ProjectUser::inRandomOrder()->first();
        $tenantId = optional($projectUser?->project)->tenant_id ?? Tenant::inRandomOrder()->value('id');

        $start = Carbon::now()->subDays($this->faker->numberBetween(1, 120))
            ->setTime($this->faker->numberBetween(5, 21), $this->faker->numberBetween(0, 59));
        $durationMinutes = $this->faker->numberBetween(10, 180);
        $end = (clone $start)->addMinutes($durationMinutes);

        return [
            'title' => $this->faker->sentence(3),
            'project_user_id' =>  $projectUser?->id,
            'tenant_id' => $tenantId,
            'group_code' => strtoupper($this->faker->lexify('GRP-???')),
            'car_id' => Car::inRandomOrder()->value('id'),
            'start_time' => $start,
            'end_time' => $end,
        ];
    }
}
