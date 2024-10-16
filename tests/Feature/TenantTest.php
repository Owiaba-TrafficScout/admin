<?php

use App\Models\Project;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(); // This will run DatabaseSeeder by default
});

it('can create a tenant', function () {
    // Create a tenant using the factory
    $tenant = Tenant::factory()->create();

    // Assert the tenant exists in the database
    expect($tenant)->toBeInstanceOf(Tenant::class)
        ->and($tenant->exists)->toBeTrue();
});

it('has many subscriptions', function () {
    // Create a tenant using the factory
    $tenant = Tenant::factory()->create();

    //associate subscription to tenant
    Subscription::factory()->create(['tenant_id' => $tenant->id]);
    $subscriptions = $tenant->subscriptions;

    // Assert the tenant has many subscriptions
    expect($subscriptions)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);

    //Assert if any of the subscription is an instance of Subscription class
    expect($subscriptions->first())->toBeInstanceOf(Subscription::class);
});

it('has one current subscription', function () {
    $tenant = Tenant::factory()->create();

    // Create subscriptions for the tenant
    $inactiveSubscription = Subscription::factory()->create([
        'tenant_id' => $tenant->id,
        'status_id' => 2,
    ]);

    $activeSubscription = Subscription::factory()->create([
        'tenant_id' => $tenant->id,
        'status_id' => 1,
    ]);

    //assert tenant has no subscription
    expect($tenant->currentSubscription->id)->toBe($activeSubscription->id);
});

it('can get all projects for the tenant', function () {
    // Create a tenant
    $tenant = Tenant::factory()->create();

    // Create subscriptions for the tenant
    $subscription1 = Subscription::factory()->create(['tenant_id' => $tenant->id]);
    $subscription2 = Subscription::factory()->create(['tenant_id' => $tenant->id]);

    // Create projects for the subscriptions
    $project1 = Project::factory()->create(['subscription_id' => $subscription1->id]);
    $project2 = Project::factory()->create(['subscription_id' => $subscription2->id]);

    // Assert that the projects relationship returns the correct projects
    $projects = $tenant->projects;

    expect($projects)->toHaveCount(2);
    expect($projects->pluck('id'))->toContain($project1->id, $project2->id);
});
