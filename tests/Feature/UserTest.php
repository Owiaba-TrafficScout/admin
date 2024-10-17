<?php

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});
test('A user can check if they are an admin in any project', function () {
    // Create a user
    $user = User::factory()->create();

    // Create a project
    $project = Project::factory()->create();

    // Create a role
    $role = Role::where('name', 'project admin')->first();

    //expect user is not an admin in any project
    expect($user->isAdminInAnyProject())->toBeFalse();

    // Attach the user to the project with the specified role
    $project->users()->attach($user->id, [
        'role_id' => $role->id,
        'joined_at' => now(),
    ]);

    // Refresh the user instance to load the relationships
    $user->refresh();

    // Assert that the user is an admin in at least one project
    expect($user->isAdminInAnyProject())->toBeTrue();
});
