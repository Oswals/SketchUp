<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks (Laravel 11)
|--------------------------------------------------------------------------
*/

app()->booted(function () {
    $schedule = app(Schedule::class);

    $schedule->command('conversions:cleanup-pending')->hourly();  // temp_uploads
    $schedule->command('conversions:cleanup')->cron('0 */5 * * *');
    $schedule->command('conversions:cleanup-orphans')->cron('0 */5 * * *');
    $schedule->command('temp:clean-uploads')->cron('0 */5 * * *');
});
