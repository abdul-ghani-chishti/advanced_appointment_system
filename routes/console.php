<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('applications:process-nightly')
    ->dailyAt('00:05')
    ->withoutOverlapping();
//    ->onOneServer();  //For local development, remove onOneServer() unless you are using a shared cache driver such as Redis or a database cache:
