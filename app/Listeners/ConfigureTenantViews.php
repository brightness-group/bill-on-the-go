<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Tenancy\Affects\Views\Events\ConfigureViews;

class ConfigureTenantViews
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ConfigureViews $event)
{
        if ($event->event->tenant) {
            $event->addPath(resource_path('views/tenant'));
        }
}
}
