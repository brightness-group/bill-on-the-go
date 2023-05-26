<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MigrationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (
            app()->runningInConsole() &&
            !empty($_SERVER['argv'][1]) &&
            str_contains($_SERVER['argv'][1], 'migrate') &&
            APP_EDITION == 'bdgo'
        ) {
            $mainPath = database_path('migrations');

            $this->loadMigrationsFrom([$mainPath, $mainPath . '/' . APP_EDITION]);
        }
    }
}
