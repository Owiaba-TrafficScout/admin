<?php

use App\Models\Role;
use App\Models\TenantRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(fn() => $this->seed());
test('can create TenantRole', function () {
    $role = TenantRole::factory()->create();

    expect($role)->toBeInstanceOf(TenantRole::class);
});
