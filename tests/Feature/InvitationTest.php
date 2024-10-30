<?php

use App\Models\Invitation;
use App\Models\Project;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});

test('can create invitation via factory', function () {
    $invitation = Invitation::factory()->create();

    expect($invitation->exists())->toBeTrue();
    expect($invitation->email)->toBeString();
    expect($invitation->role_id)->toBeInt();
    expect($invitation->project_id)->toBeInt();
    expect($invitation->accepted)->toBeBool();
    expect($invitation->token)->toBeString();
    expect($invitation->expires_at)->toBeInstanceOf(DateTime::class);
});

test('it belongs to a project', function () {
    /**
     * Create project
     * Create invitation for project
     * Check if invitation belongs to project
     */
    $project = Project::factory()->create();
    $invitation = Invitation::factory()->for($project)->create();

    expect($invitation->project->id)->toBe($project->id);
});

test('it can send invite via api', function () {
    /**
     * Create Tenant
     * Create an active subscription for tenant
     * Create User
     * add user to tenant as admin
     * Create Project for tenant
     * Create Invitation for project
     * Add tenant Id and project Id to session
     * Authenticate user
     * Send invitation via api
     * Assert that a redirect was made
     * Assert that a success message was sent
     */
    $tenant = Tenant::factory()->create();
    //create a subscription
    $subscription = Subscription::factory()->withTenantId($tenant->id)->withStatusId(1)->create();

    $user = User::factory()->create();
    $tenant->users()->attach($user->id, ['tenant_role_id' => 1]);
    $project = Project::factory()->for($tenant)->create();
    $invitation = Invitation::factory()->for($project)->create();

    //add tenant Id and project Id to session
    session()->put('tenant_id', $tenant->id);
    session()->put('project_id', $project->id);

    //authenticate user
    $this->actingAs($user);

    $response = $this->post(route('projects.invite', ['project' => $project->id]), [
        'email' => $invitation->email,
    ]);
    $response->status(302);
    $response->assertSessionHas('success', 'Invitation sent successfully');
});

test('it can accept invitation for users not in the system', function () {
    /**
     * Create invitation
     * Create signed url
     * retrieve token from invitation
     * send a get request to the accept route with the token
     * -----Source of Truth-----
     * user is redirected to Inertia::render('invitation/register')
     */

    $invitation = Invitation::factory()->create(['expires_at' => now()->addDays(7)]);
    $token = $invitation->token;
    $signedUrl = URL::temporarySignedRoute(
        'invite.accept',
        now()->addDays(7),
        ['token' => $token]
    );

    $response = $this->get($signedUrl);
    $response->assertInertia(
        fn($page) => $page
            ->component('Invitation/Register')
    );
});

test('it can accept invitation for users already in the system', function () {
    /**
     * Create user
     * Create invitation with user email
     * Create signed url
     * retrieve token from invitation
     * send a get request to the accept route with signed url
     * -----Source of Truth-----
     * a redirect is made
     * user is added to the project
     * user's role in the project is correctly set
     */
    $user = User::factory()->create();
    $invitation = Invitation::factory()->create(['expires_at' => now()->addDays(7), 'email' => $user->email]);
    $token = $invitation->token;
    $signedUrl = URL::temporarySignedRoute(
        'invite.accept',
        now()->addDays(7),
        ['token' => $token]
    );

    $response = $this->get($signedUrl);
    $response->status(302);
    $user = User::where('email', $invitation->email)->first();
    expect($user->exists())->toBeTrue();
    expect($user->projects->pluck('id'))->toContain($invitation->project_id);
    expect($user->projects->find($invitation->project_id)->pivot->role_id)->toBe($invitation->role_id);
});

test('it can register user if user is not already in system and has accepted the invitation', function () {
    /**
     * Create invitation
     * Hit endpoint with user details [email, password, password confirmation and name]
     * -----Source of Truth-----
     * User is created
     * User is added to the project
     * User's role in the project is correctly set
     * User is redirected to the login page
     */

    $invitation = Invitation::factory()->create(['expires_at' => now()->addDays(7)]);

    $response = $this->post(route('invite.register', ['token' => $invitation->token]), [
        'email' => $invitation->email,
        'password' => 'password',
        'password_confirmation' => 'password',
        'name' => 'John Doe',
    ]);

    $response->status(302);
    $user = User::where('email', $invitation->email)->first();
    expect($user->exists())->toBeTrue();
    expect($user->projects->pluck('id'))->toContain($invitation->project_id);
    expect($user->projects->find($invitation->project_id)->pivot->role_id)->toBe($invitation->role_id);
    $response->assertRedirect(route('login'));
});
