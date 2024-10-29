<?php

use App\Models\CarType;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
