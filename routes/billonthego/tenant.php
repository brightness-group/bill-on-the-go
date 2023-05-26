<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\InvitationHandler;
use App\Http\Controllers\OAuthTeamviewer;
use App\Http\Controllers\ActivityOverviewController;
use App\Http\Controllers\VSDB\DataSubjectRequestController;

use Livewire\Controllers\FileUploadHandler;
use Livewire\Controllers\FilePreviewHandler;
use Livewire\Controllers\HttpConnectionHandler;
use Livewire\Controllers\LivewireJavaScriptAssets;

use App\Http\Livewire\Tenant\CustomerComponent;
use App\Http\Livewire\Tenant\ShowCustomerComponent;
use App\Http\Livewire\Tenant\CustomerConnectionsComponent;
use App\Http\Livewire\Tenant\ConnectionEditComponent;
use App\Http\Livewire\Tenant\ConnectionBorderLineComponent;
use App\Http\Livewire\Tenant\ShowTenantInfo;
use App\Http\Livewire\Tenant\FileUploadComponent;
use App\Http\Livewire\Tenant\Dashboard;
use App\Http\Livewire\Tenant\PageAccountSetting;
use App\Http\Livewire\Tenant\TodoAppComponent;
use App\Http\Livewire\Tenant\GlobalSearchComponent;
use App\Http\Livewire\Tenant\ShowAccountSettingComponent;
use App\Http\Livewire\Tenant\bdgo\AjaxController;
use App\Http\Livewire\Tenant\bdgo\ShowDocumentComponent;

Route::get('/invitation-receive/{id}/{token}',[InvitationHandler::class,'initController']);
Route::post('/invitation-receive/', [InvitationHandler::class,'update'])->name('invitation-update');

Route::get('check-session', [ActivityOverviewController::class, 'checkSessionExpired'])
    ->middleware('web')->name('check.session.expired');

Route::get('test',function (){
    $pagePending = true;
    $i = 1;
    $offset_id = null;
    while ($pagePending) {

        // check page is not pending
        if($i >= 5){
            $pagePending = false;
            $offset_id = '';
        }else{ // page is pending i.e. re-call api and push data to job batch.
            $offset_id = '<hr>';
        }
        echo $i ;
        echo '<br>';
        echo $offset_id ;
        echo '<br>';
        $i++;
    }
//    dd($pagePending);
}
);

// locale Route
Route::get('lang/{locale}',[LanguageController::class,'swap'])->name('locale');

Route::middleware(['web', 'auth:tenant', 'verified'])->group(function () {

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/dashboard-ecommerce',function (){
            return view('tenant.pages.dashboard-ecommerce');
        })->name('dashboard.ecommerce');
        Route::get('/company/data', fn() => redirect('show-account-settings'))->name('tenant.show');
        Route::get('/documents', ShowDocumentComponent::class)->name('documents');
    });

    Route::get('/', fn() => redirect('dashboard'));

    Route::get('/show-account-settings', ShowAccountSettingComponent::class)->name('account.settings');

    // Route::view('/page-account-settings', 'tenant.pages.page-account-settings')->name('ww');
    Route::get('/tariffs', PageAccountSetting::class)->name('pages.tariffs');
    Route::get('/page-account-settings', fn() => redirect('show-account-settings'))->name('pages.account.settings');

    Route::get('/customers/list', CustomerComponent::class)->name('customers.list');
    Route::get('/customers/{customer?}', ShowCustomerComponent::class)->name('customers.show');
    Route::get('/get-users-by-customer/{customer?}', [ActivityOverviewController::class, 'getUsersByCustomer'])->name('get.users.by.customer');
    Route::get('/get-devices-by-customer/{customer?}', [ActivityOverviewController::class, 'getDevicesByCustomer'])->name('get.devices.by.customer');
    Route::get('/get-tariffs-by-customer/{customer?}', [ActivityOverviewController::class, 'getTariffsByCustomer'])->name('get.tariffs.by.customer');
//    Route::get('/check/calendar/access', [ActivityOverviewController::class, 'checkCalendarAccess'])->name('check.calendar.access');

    Route::get('/connection/file/upload',FileUploadComponent::class)->name('file.upload');
    Route::get('/connections/{customer?}', [ActivityOverviewController::class, 'index'])->name('customer.connections');
    Route::post('/connections-export', [ActivityOverviewController::class, 'exportPdf'])->name('customer.connections.export.pdf');
    Route::post('/get-connections-by-filter/{customer?}', [ActivityOverviewController::class, 'getConnectionReportsByFilter'])->name('customer.get.connection.reports.by.filter');
    Route::post('/connection/change-billing', [ActivityOverviewController::class, 'changeBillColumn'])->name('customer.connection.report.change.billing');
    Route::post('/connection/change-bulk-status', [ActivityOverviewController::class, 'updateBulkConnectionStatus'])->name('customer.connection.bulk.status');

    Route::get('/edit-connection-report/{connection?}/{type?}', [ActivityOverviewController::class, 'showConnectionEditForm'])->name('customer.connection.edit');

    Route::get('/connections-old/{customer?}', CustomerConnectionsComponent::class)->name('customer.connections.old');

    Route::get('/connection/{connection}', ConnectionEditComponent::class)->name('connection.edit');
    Route::get('/connection/borderlimit/{connection}', ConnectionBorderLineComponent::class)->name('connection.border_limit.show');

    Route::get('/app-todo', TodoAppComponent::class)->name('app.todo');

    Route::view('/app-email', 'tenant.pages.app-email')->name('app-email');
//    Route::view('/app-todo', 'tenant.pages.app-todo')->name('app-todo');
    Route::view('/app-chat', 'tenant.pages.app-chat')->name('app-chat');
    Route::view('/app-calendar', 'tenant.pages.app-calendar')->name('app-calendar');


    // Global search ajax.
    Route::get('global/search', [GlobalSearchComponent::class, 'search'])->name('global.search');

    // Ajax routes.
    Route::group(['prefix' => 'ajax'], function () {
        Route::post('/setCookie', [AjaxController::class, 'SetCookie'])->name('set-cookie-ajax');
        Route::get('/computeDashboardWidgets', [AjaxController::class, 'ComputeDashboardWidgets'])->name('compute-dashboard-widgets-ajax');
    });

});


Route::group(['middleware' => config('fortify.middleware', ['web'],)], function () {
    $enableViews = config('fortify.views', true);
    // Authentication...
    if ($enableViews) {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->middleware(['guest'])
            ->name('login');
    }

    $limiter = config('fortify.limiters.login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest',
            $limiter ? 'throttle:'.$limiter : null,
        ]));


    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

    // Password Reset...
    if (Features::enabled(Features::resetPasswords())) {
        if ($enableViews) {
            Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware(['guest'])
                ->name('password.request');

            Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware(['guest'])
                ->name('password.reset');
        }

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->middleware(['guest'])
            ->name('password.email');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->middleware(['guest'])
            ->name('password.update');
    }

    // Registration...
    if (Features::enabled(Features::registration())) {
        if ($enableViews) {
            Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware(['guest'])
                ->name('register');
        }

        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware(['guest']);
    }

    // Email Verification...
    if (Features::enabled(Features::emailVerification())) {
        if ($enableViews) {
            Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware(['auth'])
                ->name('verification.notice');
        }

        Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['auth', 'signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['auth', 'throttle:6,1'])
            ->name('verification.send');
    }

    // Profile Information...
    if (Features::enabled(Features::updateProfileInformation())) {
        Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
            ->middleware(['auth'])
            ->name('user-profile-information.update');
    }

    // Passwords...
    if (Features::enabled(Features::updatePasswords())) {
        Route::put('/user/password', [PasswordController::class, 'update'])
            ->middleware(['auth'])
            ->name('user-password.update');
    }

    // Password Confirmation...
    if ($enableViews) {
        Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->middleware(['auth'])
            ->name('password.confirm');

        Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
            ->middleware(['auth'])
            ->name('password.confirmation');
    }

    Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware(['auth']);

    // Two Factor Authentication...
    if (Features::enabled(Features::twoFactorAuthentication())) {
        if ($enableViews) {
            Route::get('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
                ->middleware(['guest'])
                ->name('two-factor.login');
            Route::get('/recovery-code', function () {
                return view('tenant.auth.recovery-code');
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

//Route::group(['middleware' => config('jetstream.middleware', ['tenant'])], function () {
//  Route::group(['middleware' => ['auth:tenant', 'verified']], function () {
//      // User & Profile...
//      Route::get('/user/profile', [UserProfileController::class, 'show'])
//                  ->name('profile.show');
//
//      // API...
//      if (Jetstream::hasApiFeatures()) {
//          Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
//      }
//
//      // Teams...
//      if (Jetstream::hasTeamFeatures()) {
//          Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
//          Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
//          Route::put('/current-team', [CurrentTeamController::class, 'update'])->name('current-team.update');
//      }
//  });
//});
//
//
//Route::middleware(['auth:tenant'])->get('/dashboard', function () {
//    return view('dashboard');
//})->name('dashboard');

//livewire


Route::get('/livewire/livewire.js', [LivewireJavaScriptAssets::class, 'source']);
Route::get('/livewire/livewire.js.map', [LivewireJavaScriptAssets::class, 'maps']);

Route::post('/livewire/message/{name}', HttpConnectionHandler::class)
    ->middleware(config('livewire.middleware_group', 'web'));

Route::post('/livewire/upload-file', [FileUploadHandler::class, 'handle'])
    ->middleware(config('livewire.middleware_group', 'web'))
    ->name('livewire.upload-file');

Route::get('/livewire/preview-file/{filename}', [FilePreviewHandler::class, 'handle'])
    ->middleware(config('livewire.middleware_group', 'web'))
    ->name('livewire.preview-file');


// vsdb app routes

Route::prefix('bdgo')->group(function () {
    Route::get('/', fn()=> redirect()->route('data-subject-request'));
    Route::get('/data-subject-request', [DataSubjectRequestController::class, 'index'])->name('data-subject-request');
    Route::get('/data-subject-request/create', [DataSubjectRequestController::class, 'create'])->name('data-subject-request-create');
    Route::get('/data-subject-request/edit', [DataSubjectRequestController::class, 'edit'])->name('data-subject-request-create');
});
