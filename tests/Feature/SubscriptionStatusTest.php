<?php

use App\Models\SubscriptionStatus;

it('can create a SubscriptionStatus', function () {
    // Create a SubscriptionStatus using the factory
    $SubscriptionStatus = SubscriptionStatus::factory()->create();

    // Assert the SubscriptionStatus exists in the database
    expect($SubscriptionStatus)->toBeInstanceOf(SubscriptionStatus::class)
        ->and($SubscriptionStatus->exists)->toBeTrue();
});
