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

it('can get the tenant through the subscription', function () {
    // Create a tenant
    $tenant = Tenant::factory()->create();

    // Create a subscription for the tenant
    $subscription = Subscription::factory()->create(['tenant_id' => $tenant->id]);

    // Create a project for the subscription
    $project = Project::factory()->create(['subscription_id' => $subscription->id]);

    // Assert that the tenant relationship returns the correct tenant
    $relatedTenant = $project->tenant;

    expect($relatedTenant->id)->toBe($tenant->id);
});

it('can get all project admins', function () {
    // Create a project
    $project = Project::factory()->create();

    // Create a user
    $user = User::factory()->create();

    // Create a role
    $role = Role::where('name', 'project admin')->first();

    // Attach the user to the project with the specified role
    $project->users()->attach($user->id, [
        'role_id' => $role->id,
        'joined_at' => now(),
    ]);

    // Assert that the project admins relationship returns the correct user
    $projectAdmins = $project->admins;

    expect($projectAdmins->contains($user))->toBeTrue();
});
