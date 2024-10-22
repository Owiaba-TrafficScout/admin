<?php

use App\Models\Project;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});
test('Projects can be created via factory', function () {
    // Create a Project using the factory
    $project = Project::factory()->create();

    // Assert the Project exists in the database
    expect($project)->toBeInstanceOf(Project::class)
        ->and($project->exists)->toBeTrue();
});

it('can get the project tenant', function () {
    // find tenant with active subscription
    // in this case, we are using the first tenant
    // which i have already defined in the database seeder
    // to have an active subscription
    $tenant = Tenant::first();

    // Create a project for the tenant
    $project = Project::factory()->withTenantId($tenant->id)->create();

    // Assert that the tenant relationship returns the correct tenant
    $relatedTenant = $project->tenant;

    expect($relatedTenant->id)->toBe($tenant->id);
});

it('can get all admins', function () {
    // Create a project
    $project = Project::factory()->create();

    // Create a user
    $user = User::factory()->create();

    // Create a role
    $role = Role::where('name', 'admin')->first();

    // Attach the user to the project with the specified role
    $project->users()->attach($user->id, [
        'role_id' => $role->id,
        'joined_at' => now(),
    ]);

    // Assert that the admins relationship returns the correct user
    $projectAdmins = $project->admins;

    expect($projectAdmins->contains($user))->toBeTrue();
});


test("can get all project's trips", function () {
    // create project
    $project = Project::factory()->create();

    // create user
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // attach user to project
    $project->users()->attach($user1->id, [
        'role_id' => 1,
        'joined_at' => now(),
    ]);
    $project->users()->attach($user2->id, [
        'role_id' => 1,
        'joined_at' => now(),
    ]);

    // create trip for user1
    $trip1 = $project->users->first()->pivot->trips()->create([
        'title' => 'Trip to the moon',
        'description' => 'A trip to the moon and back',
        'project_user_id' => $project->users->first()->pivot->id,
        'group_code' => 'ABC123',
        'car_id' => 1,
        'start_time' => now(),
        'end_time' => now()->addDay(),
        'trip_status_id' => 1,
    ]);

    // create trip for user2
    $trip2 = $project->users->last()->pivot->trips()->create([
        'title' => 'Trip to the sun',
        'description' => 'A trip to the sun and back',
        'project_user_id' => $project->users->last()->pivot->id,
        'group_code' => 'XYZ123',
        'car_id' => 2,
        'start_time' => now(),
        'end_time' => now()->addDay(),
        'trip_status_id' => 1,
    ]);

    // Assert that the project's trips relationship returns the correct trips
    $projectTrips = $project->trips;
    assert($projectTrips->contains($trip1));
    assert($projectTrips->contains($trip2));

    // Asseert that the total number of trips is 2
    expect($projectTrips->count())->toBe(2);
});
