<?php

use App\Models\Project;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});

it('can create a Subscription', function () {
    // Create a Subscription using the factory
    $Subscription = Subscription::factory()->create();

    // Assert the Subscription exists in the database
    expect($Subscription)->toBeInstanceOf(Subscription::class)
        ->and($Subscription->exists)->toBeTrue();
});

it('belongs to Tenant class', function () {
    // Create a Subscription using the factory
    $Subscription = Subscription::factory()->create();

    // Assert the Subscription belongs to the Tenant class
    expect($Subscription->tenant)->toBeInstanceOf(Tenant::class);
});

it('belongs to subscriptionPlans class', function () {
    // Create a Subscription using the factory
    $Subscription = Subscription::factory()->create();

    // Assert the Subscription belongs to the subscriptionPlans class
    expect($Subscription->subscriptionPlan)->toBeInstanceOf(SubscriptionPlan::class);
});
