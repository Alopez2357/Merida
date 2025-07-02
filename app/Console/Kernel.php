<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //BACKUP
        $schedule->command('backup:run')->daily();
        ///AIRDNA polygons
        $schedule->command('geo:polygon')->dailyAt('18');
        // DENUE INEGI ESTRATO
        $schedule->command('inegi:estrato')->monthlyOn(1, '12:00');
        // Division politica
        $schedule->command('inegi:division')->yearlyOn(1, 1, '22:00');
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
