<?php

use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use App\Events\Registered;
use GuzzleHttp\Psr7\Uri;
use Tests\TestCase;

use Unicodeveloper\Paystack\Facades\Paystack;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(); // This will run DatabaseSeeder by default
});

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

// test('new users can register', function () {
//     $response = $this->post('/register', [
//         'name' => 'Test User',
//         'email' => 'test@example.com',
//         'password' => 'password',
//         'password_confirmation' => 'password',
//     ]);

//     $this->assertAuthenticated();
//     $response->assertRedirect(route('dashboard', absolute: false));
// });


// test('it handles paystack callback and creates user and tenant', function () {
//     // Create a subscription plan
//     $plan = SubscriptionPlan::first();

//     // Simulate the user registration data in the session
//     Session::put('user_registration_data', [
//         'org_name' => 'Test Organization',
//         'org_email' => 'org@example.com',
//         'name' => 'Test User',
//         'email' => 'user@example.com',
//         'password' => 'password',
//         'plan_id' => $plan->id,
//     ]);

//     // Mock the Paystack payment data
//     $paymentDetails = [
//         'status' => 'success',
//         'data' => [
//             'reference' => 'test_reference',
//             'amount' => 10000,
//             'currency' => 'GHS',
//             'customer' => [
//                 'email' => 'user@example.com',
//             ],
//         ],
//     ];

//     // // Mock the Paystack facade
//     // Paystack::shouldReceive(Uri::class . '::getPaymentData')
//     //     ->andReturn($paymentDetails);

//     // Simulate the Paystack callback request
//     $response = $this->get('/paystack/callback');

//     // Assert that the tenant and user are created
//     $this->assertDatabaseHas('tenants', [
//         'name' => 'Test Organization',
//         'email' => 'org@example.com',
//     ]);

//     $this->assertDatabaseHas('users', [
//         'name' => 'Test User',
//         'email' => 'user@example.com',
//     ]);

//     //Assert tenant has subscription
//     $this->assertDatabaseHas('subscriptions', [
//         'tenant_id' => Tenant::first()->id,
//         'plan_id' => $plan->id,
//     ]);

//     // Assert that the user is logged in
//     $this->assertTrue(Auth::check());

//     // Assert that the session data is cleared
//     $this->assertSessionMissing('user_registration_data');

//     // Assert that the response is a redirect to the dashboard
//     $response->assertRedirect(route('dashboard'));
// });
