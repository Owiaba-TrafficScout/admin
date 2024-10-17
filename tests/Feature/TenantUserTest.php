<?php

use App\Models\TenantUser;

test('Can create tenant user', function () {
    $tenantUser = TenantUser::factory()->create();

    expect($tenantUser)->toBeInstanceOf(TenantUser::class);
});
