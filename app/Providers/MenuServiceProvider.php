<?php

namespace App\Providers;

use App\Models\Tenant\User;
use Illuminate\Support\ServiceProvider;
use Tenancy\Facades\Tenancy;
use Auth;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $appEdition   = config('app.app_edition');

        $menuJsonPath = "resources/data/menus/" . $appEdition;

        view()->composer([
            'tenant/theme-new/layouts/sections/menu/horizontalMenu',
            'tenant/theme-new/layouts/sections/menu/submenu',
            'tenant/theme-new/layouts/sections/menu/verticalMenu',
            'theme-new/layouts/sections/menu/verticalMenu'
        ], function($view) use($menuJsonPath) {
            // get all data from menu.json file
            if (Tenancy::getTenant()) {
                $menuJsonPath .= "/tenant";

                $verticalMenuJson = file_get_contents(base_path($menuJsonPath . '/admin/vertical-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);

                $horizontalMenuJson = file_get_contents(base_path($menuJsonPath . '/admin/horizontal-menu.json'));
                $horizontalMenuData = json_decode($horizontalMenuJson);

                $verticalMenuBoxiconsJson = file_get_contents(base_path($menuJsonPath . '/admin/vertical-menu-boxicons.json'));
                $verticalMenuBoxiconsData = json_decode($verticalMenuBoxiconsJson);

                if (Auth::check() && Auth::user()->hasRole('User')) {
                    $verticalMenuJson = file_get_contents(base_path($menuJsonPath . '/user/vertical-menu.json'));
                    $verticalMenuData = json_decode($verticalMenuJson);

                    $horizontalMenuJson = file_get_contents(base_path($menuJsonPath . '/user/horizontal-menu.json'));
                    $horizontalMenuData = json_decode($horizontalMenuJson);

                    $verticalMenuBoxiconsJson = file_get_contents(base_path($menuJsonPath . '/user/vertical-menu-boxicons.json'));
                    $verticalMenuBoxiconsData = json_decode($verticalMenuBoxiconsJson);
                }

            } else {
                //Backend
                $menuJsonPath .= "/system";

                $verticalMenuJson = file_get_contents(base_path($menuJsonPath . '/vertical-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);

                $horizontalMenuJson = file_get_contents(base_path($menuJsonPath . '/horizontal-menu.json'));
                $horizontalMenuData = json_decode($horizontalMenuJson);

                $verticalMenuBoxiconsJson = file_get_contents(base_path($menuJsonPath . '/vertical-menu-boxicons.json'));
                $verticalMenuBoxiconsData = json_decode($verticalMenuBoxiconsJson);
            }

            $view->with('menuData', [$verticalMenuData, $horizontalMenuData, $verticalMenuBoxiconsData]);
        });
    }
}
