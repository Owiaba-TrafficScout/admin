<?php

use App\Models\CarType;
use App\Models\Project;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});
test('Projects can be created via factory', function () {
    // Create a Project using the factory
    $project = Project::factory()->create();

    // Assert the Project exists in the database
    expect($project)->toBeInstanceOf(Project::class)
        ->and($project->exists)->toBeTrue();
});

it('can get the project tenant', function () {
    // find tenant with active subscription
    // in this case, we are using the first tenant
    // which i have already defined in the database seeder
    // to have an active subscription
    $tenant = Tenant::first();

    // Create a project for the tenant
    $project = Project::factory()->withTenantId($tenant->id)->create();

    // Assert that the tenant relationship returns the correct tenant
    $relatedTenant = $project->tenant;

    expect($relatedTenant->id)->toBe($tenant->id);
});

it('can get all admins', function () {
    // Create a project
    $project = Project::factory()->create();

    // Create a user
    $user = User::factory()->create();

    // Create a role
    $role = Role::where('name', 'admin')->first();

    // Attach the user to the project with the specified role
    $project->users()->attach($user->id, [
        'role_id' => $role->id,
        'joined_at' => now(),
    ]);

    // Assert that the admins relationship returns the correct user
    $projectAdmins = $project->admins;

    expect($projectAdmins->contains($user))->toBeTrue();
});


test("can get all project's trips", function () {
    // create project
    $project = Project::factory()->create();

    // create user
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // attach user to project
    $project->users()->attach($user1->id, [
        'role_id' => 1,
        'joined_at' => now(),
    ]);
    $project->users()->attach($user2->id, [
        'role_id' => 1,
        'joined_at' => now(),
    ]);

    // create trip for user1
    $trip1 = $project->users->first()->pivot->trips()->create([
        'title' => 'Trip to the moon',
        'description' => 'A trip to the moon and back',
        'project_user_id' => $project->users->first()->pivot->id,
        'tenant_id' => $project->tenant_id,
        'group_code' => 'ABC123',
        'car_id' => 1,
        'start_time' => now(),
        'end_time' => now()->addDay(),
        'trip_status_id' => 1,
    ]);

    // create trip for user2
    $trip2 = $project->users->last()->pivot->trips()->create([
        'title' => 'Trip to the sun',
        'description' => 'A trip to the sun and back',
        'project_user_id' => $project->users->last()->pivot->id,
        'tenant_id' => $project->tenant_id,
        'group_code' => 'XYZ123',
        'car_id' => 2,
        'start_time' => now(),
        'end_time' => now()->addDay(),
        'trip_status_id' => 1,
    ]);

    // Assert that the project's trips relationship returns the correct trips
    $projectTrips = $project->trips;
    assert($projectTrips->contains($trip1));
    assert($projectTrips->contains($trip2));

    // Asseert that the total number of trips is 2
    expect($projectTrips->count())->toBe(2);
});

it('stores a new project', function () {
    // Create a user and authenticate
    $user = User::factory()->create();
    Auth::login($user);

    // get tenant with active subscription
    $tenant = Tenant::first();

    //add user to tenant
    $tenant->users()->attach($user->id, [
        'tenant_role_id' => 1,
    ]);

    //store tenant_id in session
    session(['tenant_id' => $tenant->id]);

    // Simulate a POST request to the projects.store route
    $response = $this->post(route('projects.store'), [
        'name' => 'Test Project',
        'description' => 'This is a test project',
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(30)->toDateString(),
        'tenant_id' => $tenant->id,
    ]);

    // Assert the response status and database changes
    $response->assertStatus(302); // Assuming a redirect after successful store
    $this->assertDatabaseHas('projects', [
        'name' => 'Test Project',
        'description' => 'This is a test project',
        'tenant_id' => $tenant->id,
    ]);
});



it('displays the create project form for admin users', function () {
    // Create a user and authenticate
    $user = User::factory()->create();
    Auth::login($user);

    // make user an admin
    $role = Role::where('name', 'admin')->first();
    Tenant::first()->users()->attach($user->id, [
        'tenant_role_id' => $role->id,
    ]);

    //store tenant_id in session
    session(['tenant_id' => Tenant::first()->id]);

    // Create some car types
    $car_type_count = CarType::count();

    // Simulate a GET request to the projects.create route
    $response = $this->get(route('projects.create'));

    // Assert the response status and view
    $response->assertStatus(200);
    $response->assertInertia(
        fn($page) => $page
            ->component('Projects/Create')
            ->has('carTypes', $car_type_count)
    );
});

it('redirects back with error for non-admin users', function () {
    // Create a user and authenticate
    $user = User::factory()->create();
    Auth::login($user);

    // Mock the isAdminInTenant method to return false
    $this->mock(User::class, function ($mock) {
        $mock->shouldReceive('isAdminInTenant')->andReturn(false);
    });

    // Simulate a GET request to the projects.create route
    $response = $this->get(route('projects.create'));

    // Assert the response status and redirection
    $response->assertStatus(302);
    $response->assertSessionHas('error', 'You are not allowed to create a project.');
});

it('displays the add users to project form', function () {
    // Create a user and authenticate
    $user = User::factory()->create();
    Auth::login($user);

    //get tenant with active subscription
    $tenant = Tenant::first();
    //add user to tenant
    $tenant->users()->attach($user->id, [
        'tenant_role_id' => 1,
    ]);
    $project = Project::factory()->create(['tenant_id' => $tenant->id]);

    // Simulate a GET request to the project.users.create route
    $response = $this->get(route('project.users.create', $project->id));

    // Assert the response status and view
    $response->assertStatus(200);
    $response->assertInertia(
        fn($page) => $page
            ->component('Projects/AddUsers')
            ->has('project')
            ->has('users')
    );
});

it('stores users to the project', function () {
    // Create a user and authenticate
    $user = User::factory()->create();
    Auth::login($user);

    //get tenant with active subscription
    $tenant = Tenant::first();
    //add user to tenant
    $tenant->users()->attach($user->id, [
        'tenant_role_id' => 1,
    ]);

    $project = Project::factory()->create(['tenant_id' => $tenant->id]);

    // Create some users to add to the project
    $usersToAdd = User::factory()->count(3)->create();

    // Simulate a POST request to the project.users.store route
    $response = $this->post(route('project.users.store', $project->id), [
        'userIds' => $usersToAdd->pluck('id')->toArray(),
    ]);

    // Assert the response status and database changes
    $response->assertStatus(302); // Assuming a redirect after successful store
    $response->assertSessionHas('success', 'Users added to project.');

    foreach ($usersToAdd as $userToAdd) {
        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $userToAdd->id,
        ]);
    }
});
