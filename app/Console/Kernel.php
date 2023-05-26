<?php

namespace App\Console;

use App\Jobs\CalculateCustomerActualOperatingTimeJob;
use App\Jobs\ComputeDashboardWidgetsJob;
use App\Jobs\LivetrackLimitJob;
use App\Jobs\RecoverLivetrackConnectionsJob;
use App\Jobs\RemindAdminPasswordChangeJob;
use App\Jobs\TeamviewerRefreshTokenRequest;
use App\Jobs\TeamviewerRetriveFromAPIJob;
use App\Jobs\UpdateOverlapsTariffConnections;
use App\Models\Company;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $tenants = Company::all();


        if ($this->app->environment('local')) {
            $schedule->command('telescope:prune --hours=2')->daily();
        }

        $schedule->job(new RemindAdminPasswordChangeJob())->monthlyOn(2);

        foreach ($tenants as $tenant) {
            // schedule request access token.
            if ($tenant->anydesk_access_token)
                $schedule->job(new TeamviewerRefreshTokenRequest($tenant))->twiceDaily(1, 13);

            // schedule job: To recover live track connections.
            // Commentted it after LivetrackLimitJob implemented.
            // $schedule->job(new RecoverLivetrackConnectionsJob($tenant))->everyFiveMinutes();

            // To check 24h maximum time limit for livetrack.
            $schedule->job(new LivetrackLimitJob($tenant))->everyFiveMinutes();

            // schedule update data from API
            if ($tenant->anydesk_access_token)
                $schedule->job(new TeamviewerRetriveFromAPIJob($tenant))->dailyAt('03:00');

            // schedule job: To calculate the actual operating time for customer.
            $schedule->job(new CalculateCustomerActualOperatingTimeJob($tenant))->dailyAt('05:00');

            // schedule job: To compute the dashboard widgets data.
            $schedule->job(new ComputeDashboardWidgetsJob($tenant))->dailyAt('6:00');

            // schedule job: To check borderline emergence.
            $schedule->job(new UpdateOverlapsTariffConnections($tenant))->dailyAt('7:00');

        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $appEdition = APP_EDITION;

        $this->load(__DIR__ . '/Commands');

        require base_path("routes/{$appEdition}/console.php");
    }
}
