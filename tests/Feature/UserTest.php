<?php

use App\Models\Project;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\Trip;
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
    $role = Role::where('name', 'admin')->first();

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

test('it returns tenants where the user is an admin', function () {
    // Create a tenant role for admin
    $adminRole = TenantRole::where('name', 'admin')->first();
    $adminRoleId = $adminRole->id;

    // Set the TENANT_ADMIN_ROLE_ID environment variable
    config(['TENANT_ADMIN_ROLE_ID' => $adminRoleId]);

    // Create a user and tenants
    $user = User::factory()->create();
    $tenant1 = Tenant::factory()->create();
    $tenant2 = Tenant::factory()->create();

    // Attach the user to the tenants with the admin role
    $user->tenants()->attach($tenant1->id, ['tenant_role_id' => $adminRoleId]);
    $user->tenants()->attach($tenant2->id, ['tenant_role_id' => $adminRoleId]);

    // Assert that the user is an admin in the tenants
    $adminTenants = $user->tenantsWhereAdmin;
    expect($adminTenants->contains($tenant1))->toBeTrue();
    expect($adminTenants->contains($tenant2))->toBeTrue();
});

test('it checks if the user is an admin in any tenant', function () {
    // Create a tenant role for admin
    $adminRole = TenantRole::where('name', 'admin')->first();
    $adminRoleId = $adminRole->id;

    // Set the TENANT_ADMIN_ROLE_ID environment variable
    config(['TENANT_ADMIN_ROLE_ID' => $adminRoleId]);

    // Create a user and a tenant
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();

    // Attach the user to the tenant with the admin role
    $user->tenants()->attach($tenant->id, ['tenant_role_id' => $adminRoleId]);

    // Assert that the user is an admin in any tenant
    expect($user->isAdminInAnyTenant())->toBeTrue();

    // Detach the user from the tenant
    $user->tenants()->detach($tenant->id);

    // Assert that the user is not an admin in any tenant
    expect($user->isAdminInAnyTenant())->toBeFalse();
});

test("it checks if the user is an admin in the current tenant", function () {
    // Create a tenant role for admin
    $adminRole = TenantRole::where('name', 'admin')->first();
    $adminRoleId = $adminRole->id;

    // Set the TENANT_ADMIN_ROLE_ID environment variable
    config(['TENANT_ADMIN_ROLE_ID' => $adminRoleId]);

    // Create a user and a tenant
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();

    // Attach the user to the tenant with the admin role
    $user->tenants()->attach($tenant->id, ['tenant_role_id' => $adminRoleId]);

    // Assert that the user is an admin in the current tenant
    expect($user->isAdminInTenant($tenant->id))->toBeTrue();

    // Detach the user from the tenant
    $user->tenants()->detach($tenant->id);

    // Assert that the user is not an admin in the current tenant
    expect($user->isAdminInTenant($tenant->id))->toBeFalse();
});

test('it returns all trips for the user', function () {
    // Create a user
    $user = User::factory()->create();

    // Create a tenant
    $tenant = Tenant::factory()->create();

    // Attach the user to the tenant
    $user->tenants()->attach($tenant->id, ['tenant_role_id' => TenantRole::where('name', 'admin')->first()->id]);

    // Create a project
    $project = Project::factory()->create();

    // Attach the user to the project
    $project->users()->attach($user->id, [
        'role_id' => Role::where('name', 'admin')->first()->id,
        'joined_at' => now(),
    ]);

    // Create trips
    $trips = Trip::factory(3)->create([
        'project_user_id' => $project->users()->first()->pivot->id,
    ]);

    // Assert that the user has all trips
    expect($user->trips->count())->toBe(3);
});


test('It can get all trips for projects where user is admin', function () {
    //create user
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    //create tenant
    $tenant = Tenant::factory()->create();

    //create a subscription
    $subscription = Subscription::factory()->withTenantId($tenant->id)->withStatusId(1)->create();



    //associate user with tenant
    $tenant->users()->attach($user2->id, ['tenant_role_id' => 2]);
    $tenant->users()->attach($user3->id, ['tenant_role_id' => 2]);

    // create projects
    $tenant->projects()->create(['name' => 'Test Project 1', 'description' => 'This is a test project', 'start_date' => now(), 'end_date' => now()->addDays(30), 'code' => fake()->uuid()]);
    $tenant->projects()->create(['name' => 'Test Project 2', 'description' => 'This is a test project', 'start_date' => now(), 'end_date' => now()->addDays(30), 'code' => fake()->uuid()]);

    //asign $user2 to project 1 as admin
    $tenant->projects()->first()->users()->attach($user2->id, ['role_id' => 1]);

    //asign $user3 to project 1 as enumerator
    $tenant->projects()->first()->users()->attach($user3->id, ['role_id' => 2]);
    //assign $user2 to project 2 as enumerator
    $tenant->projects->get(1)->users()->attach($user2->id, ['role_id' => 2]);

    // get project user for project 1 user2
    $projectUser = $tenant->projects->first()->users->first()->pivot;

    //get project user for project 1 user3
    $projectUser2 = $tenant->projects->first()->users->get(1)->pivot;

    //create trips
    Trip::factory(4)->create([
        'project_user_id' => $projectUser->id,
        'tenant_id' => $tenant->id,
    ]);

    //create trips
    Trip::factory(4)->create([
        'project_user_id' => $projectUser2->id,
        'tenant_id' => $tenant->id,
    ]);

    expect($user2->trips->count())->toBe(4);
    expect($user3->trips->count())->toBe(4);
    expect($user2->adminTrips()->count())->toBe(8);
    expect($user3->adminTrips()->count())->toBe(0);
});

test('It has one state', function () {
    /**
     * Create a user with one state
     * assert that the user has one state
     */
    $user = User::factory()->hasState()->create();
    expect($user->state)->toBeInstanceOf(\App\Models\State::class);
});
