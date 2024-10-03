<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarStatus;
use App\Models\CarType;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::factory(3)->create();

        //Roles must come first before user
        User::factory(10)->create();

        Project::factory(10)->create();

        CarType::factory(10)->create();

        CarStatus::factory(3)->create();

        Car::factory(10)->create();
    }
}
