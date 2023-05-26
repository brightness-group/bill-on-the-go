<?php

namespace App\Listeners;

use Tenancy\Identification\Events\NothingIdentified;

class NoTenantIdentified 
{
    public function handle(NothingIdentified $event) 
    {        
        
        list($subdomain) = explode('.', request()->getHost(), 2);
        if ($subdomain !== env('ADMIN_SUBDOMAIN_PREFIX')) {
            abort(404);
        }
        
    }
}