<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\View;
use Tenancy\Facades\Tenancy;

class HandleErrorViewPaths extends Handler
{
    protected function registerErrorViewPaths()
    {
        View::replaceNamespace('errors', collect(config('view.paths'))->map(function ($path) {
            if (!Tenancy::getTenant())
                return "{$path}/errors";
            else
                return "{$path}/tenant/errors";
        })->push(__DIR__.'/views')->all());
    }
}
