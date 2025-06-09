<?php

namespace Database\Seeders;

use App\Models\CarType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carTypes = [
            'Bus',
            'Minibus',
            'Tram',
            'Trolleybus',
            'Metro',
            'Train',
            'Ferry',
            'Cable Car',
            'Monorail',
            'Shuttle'
        ];

        foreach ($carTypes as $type) {
            CarType::create([
                'name' => $type,
            ]);
        }
    }
}
