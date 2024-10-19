<?php

use App\Models\Project;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\TenantRole;
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
