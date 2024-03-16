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
        // Run Queue Worker
        $schedule->command('queue:work --stop-when-empty')->everyFiveMinutes()->withoutOverlapping();
        
        // Delete temp old files daily from storage folder
        $schedule->command('app:delete-old-files')->dailyAt('00:00');
        $schedule->command('app:expire-deal-after-time')->dailyAt('00:05');
        
        // Generate Sitemap Daily for SEO
        $schedule->command('app:generate-sitemap')->dailyAt('01:00');

        // Expire Subscription after end date
        $schedule->command('app:expire-subscription')->dailyAt('00:10');

        // Send Subscription Expiry Notification
        $schedule->command('app:send-subscription-expiry')->dailyAt('09:00');

        
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
