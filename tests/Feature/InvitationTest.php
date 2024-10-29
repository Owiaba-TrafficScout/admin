<?php

use App\Models\Invitation;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
