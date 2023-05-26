<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use App\Http\Controllers\LanguageController;
use Laravel\Fortify\Features;
use App\Http\Livewire\ShowCompanyComponent;
use App\Http\Livewire\CompanyComponent;
use App\Http\Livewire\SystemSubdomainsComponent;
use App\Http\Livewire\SystemUsersComponent;
use App\Http\Livewire\GlobalSearchComponent;
use App\Http\Controllers\InvitationHandler;
use App\Http\Controllers\OAuthTeamviewer;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/invitation-receive/{id}/{token}',[InvitationHandler::class,'initController']);
Route::post('/invitation-receive/', [InvitationHandler::class,'update'])->name('invitation-update');

// locale Route
Route::get('lang/{locale}',[LanguageController::class,'swap'])->name('locale');

Route::middleware(['auth:web','verified'])->group(function () {

    Route::get('/', fn() => redirect('companies/list'));
    Route::view('/page-account-settings', 'pages.page-account-settings')->name('pages.account.settings');

    Route::group(['prefix' => 'companies'], function () {
        Route::get('list',CompanyComponent::class)->name('list.companies');
        Route::get('show/{company?}', ShowCompanyComponent::class)->name('show.companies');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('list', SystemUsersComponent::class)->name('users.system')->middleware('can:user-show-list');
    });

    Route::group(['prefix' => 'subdomains'], function () {
        Route::get('list', SystemSubdomainsComponent::class)->name('subdomain.system')->middleware('can:subdomain-show-list');
    });

    // Global search ajax.
    Route::get('global/search', [GlobalSearchComponent::class, 'search'])->name('global.search');

    // Route for anydesk component infobox.
    Route::get('oauth2/callback/{route}', [OAuthTeamviewer::class, 'oauthCallback'])->name('anydesk_callback');
});

Route::group(['middleware' => config('fortify.middleware', ['web'],)], function () {
    $enableViews = config('fortify.views', true);
    // Two Factor Authentication...
    if (Features::enabled(Features::twoFactorAuthentication())) {
        if ($enableViews) {
            Route::get('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
                ->middleware(['guest'])
                ->name('two-factor.login');
            Route::get('/recovery-code', function () {
                return view('auth.recovery-code');
            })
                ->middleware(['guest'])
                ->name('two-factor-recovery-code.login');
        }

        Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
            ->middleware(['guest']);

        $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
            ? ['auth', 'password.confirm']
            : ['auth'];

        Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
            ->middleware($twoFactorMiddleware);

        Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
            ->middleware($twoFactorMiddleware);

        if ($enableViews) {
            Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
                ->middleware($twoFactorMiddleware);

            Route::get('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
                ->middleware($twoFactorMiddleware);
        }

        Route::post('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
            ->middleware($twoFactorMiddleware);
    }
});





