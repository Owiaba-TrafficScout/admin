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
    $role = Role::where('name', 'project admin')->first();
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
    expect($role->projectUsers)->toHaveCount(3);

    // Assert that the role has the correct project users
    expect($role->projectUsers->pluck('project_id'))->toEqual($projects->pluck('id'));

    // Assert that i can access a single  user via the role
    expect($role->projectUsers->first()->user)->toBeInstanceOf(User::class);
    expect($role->projectUsers->first()->user->id)->toEqual($user->id);
});
