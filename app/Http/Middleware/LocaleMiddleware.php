<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Tenancy\Facades\Tenancy;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // available language in template array
        $availLocale=['en'=>'en','de'=>'de'];
        // Locale is enabled and allowed to be change
        $user = null;
        if (Tenancy::getTenant())
            $user = \App\Models\Tenant\User::where('email','=',$request->get('email'))->first();
        else
            $user = User::where('email','=',$request->get('email'))->first();
        if (!is_null($user)) {
            app()->setLocale($user->locale);
        }
        else
        if(session()->has('locale') && array_key_exists(session()->get('locale'),$availLocale)){
            // Set the Laravel locale
            app()->setLocale(session()->get('locale'));
        }

        // Set default date format for bdgo edition.
        // https://team-1614110070467.atlassian.net/browse/VD-4?focusedCommentId=10934
        if (APP_EDITION == 'bdgo') {
            config(['bdgo.date_format' => (app()->currentLocale() == 'de' ? 'DD.MM.Y' : 'Y-MM-DD')]);
        }

        return $next($request);
    }
}
