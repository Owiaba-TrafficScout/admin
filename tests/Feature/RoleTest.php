<?php

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});

test('A role has many project users', function () {
    // Retrieve the role instance
    $role = Role::where('name', 'admin')->first();
    $role_projects_count = $role->projectUsers()->count();
    $projects = Project::factory()->count(3)->create();
    $user = User::factory()->create();

    // Attach the projects to the users with the specified role
    foreach ($projects as $project) {
        $user->projects()->attach($project->id, [
            'role_id' => $role->id,
            'joined_at' => now(),
        ]);
    }

    // Assert that the role has many project users
    expect($role->projectUsers)->not->toBeEmpty();

    // Assert that the role has the correct number of project users
    expect($role->projectUsers)->toHaveCount(3 + $role_projects_count);


    // Assert that i can access a single  user via the role
    expect($role->projectUsers->first()->user)->toBeInstanceOf(User::class);
    expect($role->projectUsers->last()->user->id)->toEqual($user->id);
});
