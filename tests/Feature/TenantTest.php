<?php

use App\Models\Project;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
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

    // Create active subscriptions for the tenant
    $subscription = Subscription::factory()->withStatusId(1)->withTenantId($tenant->id)->create();


    // Create projects for the tenant
    $project1 = Project::factory()->withTenantId($tenant->id)->create();
    $project2 = Project::factory()->withTenantId($tenant->id)->create();

    // Assert that the projects relationship returns the correct projects
    $projects = $tenant->projects;

    expect($projects)->toHaveCount(2);
    expect($projects->pluck('id'))->toContain($project1->id, $project2->id);
});

test('can get all tenant admins', function () {
    // Create a tenant
    $tenant = Tenant::factory()->create();

    // Create subscriptions for the tenant
    $subscription = Subscription::factory()->withStatusId(1)->withTenantId($tenant->id)->create();

    // Create projects for the tenant
    $project1 = Project::factory()->withTenantId($tenant->id)->create();
    $project2 = Project::factory()->withTenantId($tenant->id)->create();

    // Create users
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();


    // Create users for the tenant
    $tenant->users()->attach($user1->id, ['tenant_role_id' => env('TENANT_ADMIN_ROLE_ID')]);
    $tenant->users()->attach($user2->id, ['tenant_role_id' => env('TENANT_ADMIN_ROLE_ID')]);

    // Assert that the tenant admins relationship returns the correct users
    $admins = $tenant->admins;

    expect($admins)->toHaveCount(2);
    expect($admins->pluck('id'))->toContain($user1->id, $user2->id);
});

it('has many projects', function () {
    //find tenant with active subscription
    //In tihs case we are using the first tenant
    //which i have already difined in my seeder
    //to have an active subscription
    $tenant = Tenant::first();
    $tenant->projects()->saveMany(Project::factory(3)->withTenantId($tenant->id)->make());


    // Assert the Subscription has many projects
    expect($tenant->projects)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);

    //Assert if any of the project is an instance of Project class
    expect($tenant->projects->first())->toBeInstanceOf(\App\Models\Project::class);
});

it('cannot create a project without an active subscription', function () {
    // Create a tenant
    $tenant = Tenant::factory()->create();

    // Attempt to create a project without an active subscription
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Cannot create a project without an active subscription.');

    Project::factory()->create(['tenant_id' => $tenant->id]);
});

it('can create a project with an active subscription', function () {
    // Create a tenant
    $tenant = Tenant::factory()->create();

    // Create an active subscription for the tenant
    Subscription::factory()->create([
        'tenant_id' => $tenant->id,
        'status_id' => 1, // Assuming 1 is the status ID for active
    ]);

    // Create a project with an active subscription
    $project = Project::factory()->create(['tenant_id' => $tenant->id]);

    expect($project)->toBeInstanceOf(Project::class);
});
