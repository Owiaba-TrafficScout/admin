<?php

use App\Models\Role;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(fn() => $this->seed());
test('can create TenantRole', function () {
    $role = TenantRole::factory()->create();

    expect($role)->toBeInstanceOf(TenantRole::class);
});

test('TenantRole  is correctly related to TenantUser', function () {
    $role = TenantRole::factory()->create();
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();

    $tenant->users()->attach($user->id, [
        'tenant_role_id' => $role->id
    ]);

    //Assert $user has the right role for this tenant
    expect($tenant->users->find($user->id)->pivot->role->id)->toBe($role->id);

    // Assert I can get i can get all tenant users with this role
    expect($tenant->users->filter(fn($user) => $user->pivot->role->id === $role->id)->count())->toBe(1);
});
