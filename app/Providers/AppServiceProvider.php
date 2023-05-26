<?php

namespace App\Providers;

use App\Exceptions\Handler;
use App\Http\Middleware\TrimStrings;
use App\Models\Company;
use App\Models\Tenant\Sanctum\PersonalAccessToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Exceptions\RegisterErrorViewPaths;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Sanctum\Sanctum;
use Laravel\Telescope\Telescope;
use Livewire\ObjectPrybar;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Define app edition constant.
        define('APP_EDITION', config('app.app_edition'));

        // ignore Telescope migrations.
        Telescope::ignoreMigrations();

        $this->app->resolving(ResolvesTenants::class, function (ResolvesTenants $resolver) {
            $resolver->addModel(Company::class);
            return $resolver;
        });

        $this->app->singleton(LoginResponse::class,\App\Http\Responses\LoginResponse::class);
        $this->app->singleton(TwoFactorLoginResponse::class,\App\Http\Responses\TwoFactorLoginResponse::class);
        $this->app->singleton(Handler::class,\App\Exceptions\HandleErrorViewPaths::class);

//        $this->app['queues']->createPayloadUsing(function () {
//           return Tenancy::getTenant() ? ['tenant_id' => Tenancy::getTenant()->getTenantKey()] : [];
//        });

        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression)->format('d.m.Y H:i'); ?>";
        });

        Validator::extend('customPassCheckHashed', function ($attribute, $value, $parameters) {
            if (!Hash::check($value, $parameters[0])) {
                return false;
            }
            return true;
        });

        Validator::replacer('customPassCheckHashed', function ($message, $attribute, $rule, $parameters) {
            return 'The password you provided no match with the current user password';
        });

        Validator::replacer('regex', function ($message, $attribute, $rule, $parameters) {
            if (in_array($attribute, ['email', 'billing_email', 's_email', 'p_email', 'contact_email'])) {
                return __("locale.The Email format is invalid") . " " . __("locale.e.g. xxx@xx.com");
            } elseif (in_array($attribute, ['website'])) {
                return $message . " " . __("locale.e.g. www.xxx.com");
            }

            return $message;
        });

        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
        });

    }
}
