<?php

namespace Database\Seeders;

use App\Models\SubscriptionStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['active', 'inactive', 'cancelled'];
        //create statuses
        foreach ($statuses as $status) {
            SubscriptionStatus::factory()->create(['name' => $status]);
        }
    }
}
