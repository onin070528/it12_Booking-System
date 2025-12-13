<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule full payment reminders to run daily
Schedule::command('bookings:send-full-payment-reminders')
    ->daily()
    ->at('09:00');

// Schedule event completion notifications to run daily
Schedule::command('bookings:notify-admin-event-completed')
    ->daily()
    ->at('10:00');
