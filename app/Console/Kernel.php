<?php

namespace App\Console;

use App\Console\Commands\CheckActiveAdvertises;
use App\Console\Commands\SubscriptionChecker;
use App\Console\Commands\SupportTicketCloseCommand;
use App\Console\Commands\Test;
use App\Console\Commands\UpdateAdvertisePublished;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    protected $commands = [
        CheckActiveAdvertises::class,
        SubscriptionChecker::class,
        SupportTicketCloseCommand::class,
        UpdateAdvertisePublished::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        $schedule->command('close:ticked')->daily();
        $schedule->command('telescope:prune --hours=48')->daily();
        $schedule->command('check:in_active_advertises')->daily();
        $schedule->command('check:subscriptions')->daily();
        $schedule->command('sanctum:prune-expired --hours=24')->daily();
        $schedule->command('publications:update-status')->everyFiveMinutes();
//        $schedule->command('test:command')->everyMinute(); // Замените на нужное расписание
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
