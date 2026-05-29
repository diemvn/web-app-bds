<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('hosty:generate-invoices')->monthlyOn(25, '02:00');
Schedule::command('hosty:mark-overdue')->dailyAt('01:00');
