<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:send-invoice-reminders-command')->dailyAt('09:00');
