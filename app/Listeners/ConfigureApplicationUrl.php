<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Tenancy\Affects\URLs\Events\ConfigureURL;

class ConfigureApplicationUrl
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
    public function handle(ConfigureURL $event)
    {
        $url = env('APP_URL');
        $app = env('ADMIN_SUBDOMAIN_PREFIX');

        if ($tenant = $event->event->tenant) {
            $newUrl = str_replace($app, $tenant->subdomain, $url);
            $event->changeRoot($newUrl);
//            dd($newUrl);
        }
    }
}
