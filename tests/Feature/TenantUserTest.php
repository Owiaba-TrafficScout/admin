<?php

use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});

test('Can create tenant user', function () {
    $tenantUser = TenantUser::factory()->create();

    expect($tenantUser)->toBeInstanceOf(TenantUser::class);
});

test('Can associate user to a tenant', function () {
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();
    $role = TenantRole::first();

    $tenant->users()->attach($user->id, [
        'tenant_role_id' => $role->id
    ]);

    expect($tenant->users->contains($user))->toBeTrue();
});

test('Can associate a tenant to a user', function () {
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();
    $role = TenantRole::first();

    $user->tenants()->attach($tenant->id, [
        'tenant_role_id' => $role->id
    ]);

    expect($user->tenants->contains($tenant))->toBeTrue();
});

test('Can detach a user from a tenant', function () {
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();
    $role = TenantRole::first();

    $tenant->users()->attach($user->id, [
        'tenant_role_id' => $role->id
    ]);

    $tenant->users()->detach($user->id);

    expect($tenant->users->contains($user))->toBeFalse();
});

test('Can detach a tenant from a user', function () {
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();
    $role = TenantRole::first();

    $user->tenants()->attach($tenant->id, [
        'tenant_role_id' => $role->id
    ]);

    $user->tenants()->detach($tenant->id);

    expect($user->tenants->contains($tenant))->toBeFalse();
});

test('Can get tenant role', function () {
    $tenantUser = TenantUser::factory()->create();

    expect($tenantUser->role)->toBeInstanceOf(TenantRole::class);
});

test('Can get tenant user', function () {
    $tenantUser = TenantUser::factory()->create();

    expect($tenantUser->user)->toBeInstanceOf(User::class);
});

test('Can get tenant', function () {
    $tenantUser = TenantUser::factory()->create();

    expect($tenantUser->tenant)->toBeInstanceOf(Tenant::class);
});

test("isAdmin() returns true if the user is an admin", function () {
    $tenantUser = TenantUser::factory()->create([
        'tenant_role_id' => TenantRole::where('name', 'admin')->first()->id
    ]);

    expect($tenantUser->isAdmin())->toBeTrue();
});
