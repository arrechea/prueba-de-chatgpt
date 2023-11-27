<?php

namespace App\Console;

use App\Models\Meeting\Meeting;
use App\Models\Waitlist\Waitlist;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\MigrationInit::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('cancel:waitlist')->everyMinute()->thenPing("https://cronhub.io/ping/65794860-63ca-11ea-9084-a9c9e5d3a00e");
//        $schedule->command('subscription:renew')->everyMinute()->thenPing("https://cronhub.io/ping/4a95c9a0-63d1-11ea-9652-9dffe7a9528d");
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
