<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['pending', 'paid', 'failed', 'canceled'];

        foreach ($statuses as $status) {
            \App\Models\PaymentStatus::firstOrCreate(['name' => $status]);
        }
    }
}
