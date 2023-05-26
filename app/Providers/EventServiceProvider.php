<?php

namespace App\Providers;

use App\Events\Jobs\RefreshTokenJobProcessedEvent;
use App\Events\TeamviewerDataRetrievalProcessed;
use App\Http\Controllers\UserCreated;
use App\Jobs\SendUserCreatedNotification;
use App\Listeners\Jobs\RefreshTokenJobProcessedListener;
use App\Listeners\LoginUserEventInfo;
use App\Listeners\LoginUserTenantInfo;
use App\Listeners\Notifications\InviteMailSendNotificationListener;
use App\Listeners\SendTVDataSyncCompleteNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;

use Laravel\Fortify\Http\Responses\LoginResponse;
use Tenancy\Identification\Events\NothingIdentified;
use App\Listeners\NoTenantIdentified;

use Tenancy\Affects\Routes\Events\ConfigureRoutes;
use App\Listeners\TenantRoutes;

use Tenancy\Hooks\Migration\Events\ConfigureMigrations;
use App\Listeners\ConfigureTenantMigrations;

use Tenancy\Affects\Models\Events\ConfigureModels;
use App\Listeners\ConfigureTenantModels;

use Tenancy\Hooks\Database\Events\Drivers\Configuring;
use App\Listeners\ConfigureTenantDatabase;

use Tenancy\Affects\Connections\Events\Resolving;
use App\Listeners\ResolveTenantConnection;

use Tenancy\Affects\Configs\Events\ConfigureConfig;
use App\Listeners\ConfigureTenantIntegrations;



class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        ConfigureConfig::class => [
            ConfigureTenantIntegrations::class
        ],
        ConfigureModels::class => [
            ConfigureTenantModels::class
        ],
        ConfigureMigrations::class => [
            ConfigureTenantMigrations::class
        ],
        ConfigureRoutes::class => [
            TenantRoutes::class
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LoginUserEventInfo::class
        ],
        NothingIdentified::class => [
            NoTenantIdentified::class
        ],
        Configuring::class => [
            ConfigureTenantDatabase::class,
        ],
        Resolving::class => [
            ResolveTenantConnection::class
        ],
        TeamviewerDataRetrievalProcessed::class => [
            SendTVDataSyncCompleteNotification::class
        ],
        \Tenancy\Affects\Connections\Events\Drivers\Configuring::class => [
            \App\Listeners\ConfigureTenantConnection::class
        ],
        \Tenancy\Affects\URLs\Events\ConfigureURL::class => [
            \App\Listeners\ConfigureApplicationUrl::class
        ],
        \Tenancy\Hooks\Migration\Events\ConfigureSeeds::class => [
            \App\Listeners\ConfigureTenantSeeds::class
        ],
        \Tenancy\Affects\Views\Events\ConfigureViews::class => [
            \App\Listeners\ConfigureTenantViews::class
        ],
        \Tenancy\Affects\Filesystems\Events\ConfigureDisk::class => [
            \App\Listeners\ConfigureTenantDisk::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
