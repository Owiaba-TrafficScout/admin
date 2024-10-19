<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('subscriptions:update-status')
    ->daily()
    ->at('00:00');


/**
 * TODO:
 * Create a Cron Job to run the command subscriptions:update-status daily at 00:00
 *
 */
