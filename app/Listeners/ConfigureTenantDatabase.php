<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Tenancy\Hooks\Database\Events\Drivers\Configuring;

class ConfigureTenantDatabase
{
    public function handle(Configuring $event)
    {
        $overrides = array_merge(
            ['host'=>'%'],
            $event->defaults($event->tenant)
        );
        $event->useConnection('mysql', $overrides);
    }
}
