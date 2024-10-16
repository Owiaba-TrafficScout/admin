<?php

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(); // This will run DatabaseSeeder by default
});

it('can create a SubscriptionPlan', function () {
    // Create a SubscriptionPlan using the factory
    $SubscriptionPlan = SubscriptionPlan::create(
        [
            "name" => "Advanced",
            'slug' => 'advanced',
            'price' => 2333.44,
            'max_projects' => 100,
            'max_users_per_project' => 100,
            'features' => json_encode(['feature1'])
        ]
    );

    // Assert the SubscriptionPlan exists in the database
    expect($SubscriptionPlan)->toBeInstanceOf(SubscriptionPlan::class)
        ->and($SubscriptionPlan->exists)->toBeTrue();
});

it('has many subscriptions', function () {
    // Create a tenant using the factory
    $plan = SubscriptionPlan::first();

    //associate subscription to plan
    Subscription::factory()->create(['plan_id' => $plan->id]);
    $subscriptions = $plan->subscriptions;

    // Assert the plan has many subscriptions
    expect($subscriptions)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);

    //Assert if any of the subscription is an instance of Subscription class
    expect($subscriptions->first())->toBeInstanceOf(Subscription::class);
});
