<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $appEdition = APP_EDITION;

        Broadcast::routes();

        require base_path("routes/{$appEdition}/channels.php");
    }
}
