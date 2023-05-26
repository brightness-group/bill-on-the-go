<?php

namespace App\Http\Responses;

use App\Providers\RouteServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Tenancy\Facades\Tenancy;

class LoginResponse implements LoginResponseContract
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
                        ? response()->json(['two_factor' => false])
                        : redirect(RouteServiceProvider::APP_HOME);
        else
            if(auth()->user()->hasRole('User'))
                return $request->wantsJson()
                    ? response()->json(['two_factor' => false])
                    : redirect(RouteServiceProvider::TENANT_USER_HOME);
            else
                return $request->wantsJson()
                    ? response()->json(['two_factor' => false])
                    : redirect(RouteServiceProvider::TENANT_HOME);
    }
}
