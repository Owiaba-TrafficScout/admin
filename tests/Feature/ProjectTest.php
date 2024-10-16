<?php

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
