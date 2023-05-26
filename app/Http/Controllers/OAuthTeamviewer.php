<?php

namespace App\Http\Controllers;

use App\Jobs\TeamviewerRetriveFromAPIJob;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\Tenant;
use Illuminate\Support\Facades\URL;
use App\Services\RetrieveDataFromAPI;

class OAuthTeamviewer extends Controller
{
    public $redirectTo;

    public function __construct()
    {
        $company = Tenancy::getTenant();

        if ($company) {
            config(['app.anydesk_client_id' => $company->anydesk_client_id]);
            config(['app.anydesk_client_secret' => $company->anydesk_client_secret]);
        }

        $this->redirectTo = config('app.asset_url').'/show-account-settings#account-vertical-connect';
    }

    public function oauthRedirect($route)
    {
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => config('app.anydesk_client_id'),
            'redirect_uri' => config('app.asset_url') . '/oauth2/callback/' . $route,
            'display' => 'popup'
        ]);

        return redirect('https://login.anydesk.com/oauth2/authorize?' . $query);
    }

    public function oauthCallback(Request $request, $route)
    {
        $response = Http::asForm()->post('https://webapi.anydesk.com/api/v1/oauth2/token', [
            'code' => $request->code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => config('app.asset_url') . '/oauth2/callback',
            'client_id' => config('app.anydesk_client_id'),
            'client_secret' => config('app.anydesk_client_secret'),
        ]);

        $status = $response->status();
        $response = $response->json();

        $company = null;

        if ($status === 200) {
            $company = Company::whereId(Tenancy::getTenant()->getTenantKey())->firstOrFail();
            $company->anydesk_access_token = $response['access_token'];
            $company->anydesk_refresh_token = $response['refresh_token'];
            $company->anydesk_access_token_for_expire_check = now();
            $company->save();

            session()->push('anydesk_callback', true);
        } else {
            session()->push('anydesk_callback_fails', true);
        }

//        if ($company) {
//            TeamviewerRetriveFromAPIJob::dispatch($company);
//        }

        $redirectTo = URL::route($route, ['#account-vertical-connect', 'fromCallback' => true]);

        return redirect()->to($redirectTo);
    }

    public function oauthRefreshToken($refreshToken)
    {
        $response = Http::asForm()->post('https://webapi.anydesk.com/api/v1/oauth2/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => config('app.anydesk_client_id'),
            'client_secret' => config('app.anydesk_client_secret'),
        ]);

        $status = $response->status();
        $response = $response->json();

        if ($status === 200) {
            $company = Company::find(Tenancy::getTenant()->getTenantKey());
            $company->anydesk_access_token = $response['access_token'];
            $company->anydesk_refresh_token = $response['refresh_token'];
            $company->anydesk_access_token_for_expire_check = now();
            $company->save();
            session()->push('anydesk_refreshToken_refreshed', true);
            return redirect()->to($this->redirectTo);

        } else {
            session()->push('anydesk_refreshToken_fails', true);
            return redirect()->to($this->redirectTo);
        }
    }

    public function oauthRevoke($token)
    {
        $response = Http::withToken($token)->post('https://webapi.anydesk.com/api/v1/oauth2/revoke');

        if ($response->status() === 200 || $response->status() === 204) {
            $company = Company::whereId(Tenancy::getTenant()->getTenantKey())->firstOrFail();
            $company->anydesk_access_token = null;
            $company->anydesk_refresh_token = null;
            $company->anydesk_access_token_for_expire_check = null;
            $company->save();
            session()->push('anydesk_revoked', true);
        } elseif ($response->status() === 401) {
            session()->push('anydesk_revoked_fails', true);
        }

        return redirect()->to($this->redirectTo);
    }
}
