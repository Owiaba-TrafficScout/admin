<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarStatus;
use App\Models\CarType;
use App\Models\License;
use App\Models\Project;
use App\Models\Role;
use App\Models\Trip;
use App\Models\TripSpeed;
use App\Models\TripStatus;
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

        CarType::factory(10)->create();

        CarStatus::factory(3)->create();

        TripStatus::factory(3)->create();

        Trip::factory(10)->create();

        //Roles must come first before user
        $users = User::all();

        License::factory(10)->create();

        //write this after licences
        $users->each(function ($user) {
            $user->licenses()->attach(License::inRandomOrder()->first());
        });
    }
}
