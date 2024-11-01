<?php

use App\Models\CarType;
use App\Models\Project;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});

test('car type is assiciated with many projects', function () {
    /**
     * Create a car type with 3 projects
     * assert car type has 3 projects
     */

    $carType = CarType::factory()->hasProjects(3)->create();
    expect($carType->projects)->toHaveCount(3);
});

test('car type has many cars', function () {
    /**
     * Create a car type with 3 cars
     * assert car type has 3 cars
     */

    $carType = CarType::factory()->hasCars(3)->create();
    expect($carType->cars)->toHaveCount(3);
});

test('car types can be added to project', function () {
    /**
     * create tenant
     * create active subscription for tenant
     * create user for tenant
     * create project for tenant
     * create 3 car types
     * create 3 new car types
     * add 3 car types to project
     * hit the project.cartype.add route with new car type ids as an array and project id
     * assert a redirect 302 status
     * assert car project has 6 car types
     * assert car project has 3 new car types
     */

    $tenant = Tenant::factory()->create();
    //create a subscription
    $subscription = Subscription::factory()->withTenantId($tenant->id)->withStatusId(1)->create();

    $user = User::factory()->create();
    $tenant->users()->attach($user->id, ['tenant_role_id' => 1]);
    $project = Project::factory()->for($tenant)->create();


    //add tenant Id and project Id to session
    session()->put('tenant_id', $tenant->id);
    session()->put('project_id', $project->id);

    //authenticate user
    $this->actingAs($user);


    $carTypes = CarType::factory(3)->create();
    $project->carTypes()->attach($carTypes->pluck('id')->toArray());
    $newCarTypes = CarType::factory(3)->create();



    $response = $this->post(route('project.cartype.add', $project->id), [
        'car_type_ids' => $newCarTypes->pluck('id')->toArray(),
    ]);

    Log::debug($response->getContent());
    $response->assertStatus(302);
    $project->refresh();
    expect($project->carTypes)->toHaveCount(6);
    expect($project->carTypes->intersect($newCarTypes))->toHaveCount(3);
});
