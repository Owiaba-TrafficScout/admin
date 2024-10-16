<?php

use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;

// uses(RefreshDatabase::class)->beforeEach(function () {
//     $this->seed(); // This will run DatabaseSeeder by default
// });

it('can create a SubscriptionPlan', function () {
    // Create a SubscriptionPlan using the factory
    $SubscriptionPlan = SubscriptionPlan::factory()->create();

    // Assert the SubscriptionPlan exists in the database
    expect($SubscriptionPlan)->toBeInstanceOf(SubscriptionPlan::class)
        ->and($SubscriptionPlan->exists)->toBeTrue();
});
