<?php

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionStatus;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    SubscriptionPlan::factory(3)->create();
    Tenant::factory(10)->create();
    SubscriptionStatus::factory(3)->create();
});

it('can create a Subscription', function () {
    // Create a Subscription using the factory
    $Subscription = Subscription::factory()->create();

    // Assert the Subscription exists in the database
    expect($Subscription)->toBeInstanceOf(Subscription::class)
        ->and($Subscription->exists)->toBeTrue();
});
