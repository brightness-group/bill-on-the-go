<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ConfigureTenantDisk
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
    public function handle($event)
    {
        if ($event->event->tenant) {
            $event->config = [
                'driver' => 'local',
                'root' => storage_path('app/public/tenants/'.$event->event->tenant->getTenantKey()),
            ];
        }
    }
}
