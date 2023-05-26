<?php

namespace App\Http\Responses;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Tenancy\Facades\Tenancy;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        if (!Tenancy::getTenant())
            return $request->wantsJson()
                        ? new JsonResponse('', 204)
                        : redirect(RouteServiceProvider::APP_HOME);
        else
            return $request->wantsJson()
                ? new JsonResponse('', 204)
                : redirect(RouteServiceProvider::TENANT_HOME);
    }
}
