<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Tenant\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Tenancy\Facades\Tenancy;

class FortifyServiceProvider extends ServiceProvider
{

    public $tenant;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::whereEmail($request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        $this->tenant = Tenancy::identifyTenant();

        if (!$this->tenant){

            Fortify::loginView(function () {
                return view('auth.login');
            });
//            Fortify::registerView(function () {
//                return view('auth.register');
//            });
            Fortify::requestPasswordResetLinkView(function () {
                return view('auth.forgot-password');
            });
            Fortify::resetPasswordView(function ($request) {
                return view('auth.reset-password', ['request' => $request]);
            });
            Fortify::verifyEmailView(function () {
                return view('auth.verify');
            });
            Fortify::confirmPasswordView(function () {
                return view('auth.passwords.confirm');
            });
            Fortify::twoFactorChallengeView(function () {
                return view('auth.two-factor-challenge');
            });

        }
        else
        {
            Fortify::loginView(function () {
                if ($this->tenant->status) {
                    return view('tenant.auth.errors.blocked', ['tenant' => $this->tenant]);
                }
                else {
                    return view('tenant.auth.login', ['tenant' => $this->tenant]);
                }
            });
//            Fortify::registerView(function () {
//                return view('tenant.auth.register');
//            });
            Fortify::requestPasswordResetLinkView(function () {
                return view('tenant.auth.forgot-password', ['tenant' => $this->tenant]);
            });
            Fortify::resetPasswordView(function ($request) {
                return view('tenant.auth.reset-password', ['request' => $request, 'tenant' => $this->tenant]);
            });
            Fortify::verifyEmailView(function () {
                return view('tenant.auth.verify', ['tenant' => $this->tenant]);
            });
            Fortify::confirmPasswordView(function () {
                return view('tenant.auth.passwords.confirm');
            });
            Fortify::twoFactorChallengeView(function () {
                return view('tenant.auth.two-factor-challenge', ['tenant' => $this->tenant]);
            });
        }
    }
}
