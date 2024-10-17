<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ["system admin", 'project admin', 'enumerator'];
        //inside pick first unique element from $roles
        collect($roles)->each(fn($role) => \App\Models\Role::factory()->create(['name' => $role]));
    }
}
