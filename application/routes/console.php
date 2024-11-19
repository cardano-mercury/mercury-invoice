<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\GenerateReports;
use App\Console\Commands\ProcessCryptoPayments;
use App\Console\Commands\SendInvoiceRemindersCommand;

// Send invoice reminder emails daily at 8 AM UTC
Schedule::command(SendInvoiceRemindersCommand::class)->dailyAt('08:00');

// Process crypto payments every five minutes
Schedule::command(ProcessCryptoPayments::class)->everyFiveMinutes();

// Process generate reports every five minutes
Schedule::command(GenerateReports::class)->everyFiveMinutes();
