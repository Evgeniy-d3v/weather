<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

app(Schedule::class)->command('weather:get-forecast')
    ->hourly()
    ->withoutOverlapping();
app(Schedule::class)->command('telegram:send-forecast-to-client')
    ->hourly()
    ->withoutOverlapping();
