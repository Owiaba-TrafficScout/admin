<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptionPlans = ['Basic', 'Pro', 'Enterprise'];

        foreach ($subscriptionPlans as $planName) {
            SubscriptionPlan::create([
                'name'                     => $planName,
                'slug'                     => \Illuminate\Support\Str::slug($planName),
                'price'                    => fake()->randomFloat(4, 0, 9999),
                'max_projects'             => fake()->randomNumber(),
                'max_users_per_project'    => fake()->randomNumber(),
                'features'                 => json_encode(['feature1', 'feature2', 'feature3']),
            ]);
        }
    }
}
