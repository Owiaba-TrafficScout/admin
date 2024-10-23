
<?php

use App\Models\Project;
use App\Models\Tenant;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});

test('payment has one tenant through project', function () {
    // get a tenant
    $tenant = Tenant::first();

    // Create a project associated with the tenant
    $project = Project::factory()->create(['tenant_id' => $tenant->id]);

    // Create a payment associated with the project
    $payment = Payment::factory()->create(['project_id' => $project->id]);

    // Assert the tenant relationship
    expect($payment->tenant)->toBeInstanceOf(Tenant::class);
    expect($payment->tenant->id)->toBe($tenant->id);
});
