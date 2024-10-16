<?php

use App\Models\ProjectUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed();
});

test('use factory to add record to ProjectUsers table', function () {
    $projectUser = ProjectUser::factory()->create();
    expect($projectUser)->toBeInstanceOf(ProjectUser::class)
        ->and($projectUser->exists)->toBeTrue();
});
