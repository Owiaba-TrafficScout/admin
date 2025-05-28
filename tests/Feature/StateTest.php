<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});

test('A state has one user', function () {
    /**
     * create a state With a single user
     * assert that the user is the owner of the state
     */
    $state = \App\Models\State::factory()->hasUser()->create();
    expect($state->user)->toBeInstanceOf(\App\Models\User::class);
});

test('A state has one project', function () {
    /**
     * create a state with a single project
     * assert that the project is the owner of the state
     */
    $state = \App\Models\State::factory()->hasProject()->create();
    expect($state->project)->toBeInstanceOf(\App\Models\Project::class);
});
