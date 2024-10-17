<?php

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});
test('Associate a User to a Project', function () {
    // Retrieve the project, user, and role instances
    $project = Project::factory()->create();
    $user = User::factory()->create();
    $role = Role::where('name', 'project admin')->first();

    // Attach the user to the project with the specified role
    $project->users()->attach($user->id, [
        'role_id' => $role->id,
        'joined_at' => now(),
    ]);

    // Assert that the user is associated with the project
    expect($project->users->contains($user))->toBeTrue();
});
test('Associate a Project to a User', function () {
    // Retrieve the project, user, and role instances
    $project = Project::factory()->create();
    $user = User::factory()->create();
    $role = Role::where('name', 'project admin')->first();

    // Attach the project to the user with the specified role
    $user->projects()->attach($project->id, [
        'role_id' => $role->id,
        'joined_at' => now(),
    ]);

    // Assert that the project is associated with the user
    expect($user->projects->contains($project))->toBeTrue();
});
test('detach a user from a project', function () {
    // Retrieve the project, user, and role instances
    $project = Project::factory()->create();
    $user = User::factory()->create();
    $role = Role::where('name', 'project admin')->first();

    // Attach the user to the project with the specified role
    $project->users()->attach($user->id, [
        'role_id' => $role->id,
        'joined_at' => now(),
    ]);

    // Detach the user from the project
    $project->users()->detach($user->id);

    // Assert that the user is not associated with the project
    expect($project->users->contains($user))->toBeFalse();
});

test('detach a project from a user', function () {
    // Retrieve the project, user, and role instances
    $project = Project::factory()->create();
    $user = User::factory()->create();
    $role = Role::where('name', 'project admin')->first();

    // Attach the project to the user with the specified role
    $user->projects()->attach($project->id, [
        'role_id' => $role->id,
        'joined_at' => now(),
    ]);

    // Detach the project from the user
    $user->projects()->detach($project->id);

    // Assert that the project is not associated with the user
    expect($user->projects->contains($project))->toBeFalse();
});

test("Associate a User to a Project with a Role", function () {
    // Retrieve the project, user, and role instances
    $project = Project::factory()->create();
    $user = User::factory()->create();
    $role = Role::where('name', 'project admin')->first();

    // Attach the user to the project with the specified role
    $project->users()->attach($user->id, [
        'role_id' => $role->id,
        'joined_at' => now(),
    ]);

    // Assert that the user is associated with the project with the specified role
    expect($project->users->contains($user))->toBeTrue();
    expect($project->users->first()->pivot->role_id)->toBe($role->id);
    expect($project->users->first()->pivot->role->name)->toBe($role->name);
});
