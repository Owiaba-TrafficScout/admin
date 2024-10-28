<?php

use App\Models\Project;
use App\Models\Tenant;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});

test("can get trip's project and projectUser", function () {
    // Create a project and a user
    $project = Project::factory()->create();
    $user = User::factory()->create();

    // Attach the user to the project
    $project->users()->attach($user->id, [
        'role_id' => 1,
        'joined_at' => now(),
    ]);

    // Create a trip for the user
    $trip = $project->users->first()->pivot->trips()->create([
        'title' => 'Trip to the moon',
        'description' => 'A trip to the moon and back',
        'project_user_id' => $project->users->first()->pivot->id,
        'tenant_id' => $project->tenant_id,
        'group_code' => 'ABC123',
        'car_id' => 1,
        'start_time' => now(),
        'end_time' => now()->addDay(),
        'trip_status_id' => 1,
    ]);

    // Assert the trip's project and projectUser
    expect($trip->project->id)->toBe($project->id);
    expect($trip->ProjectUser->id)->toBe($project->users->first()->pivot->id);
});

test("can get trip's tenant", function () {
    // Create tenant
    $tenant = Tenant::factory()->create();

    // create trip
    $trip = Trip::factory()->create([
        'tenant_id' => $tenant->id,
    ]);

    // Assert the trip's tenant
    expect($trip->tenant->id)->toBe($tenant->id);
});