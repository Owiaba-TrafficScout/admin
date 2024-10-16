<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(); // This will run DatabaseSeeder by default
});

it('uses the testing database', function () {
    $dbPath = DB::connection()->getDatabaseName();
    expect($dbPath)->toContain('testing.sqlite');
});
it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
