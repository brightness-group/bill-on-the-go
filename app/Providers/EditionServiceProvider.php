<?php

namespace App\Providers;

use App\Helpers\Helper;
use Illuminate\Support\ServiceProvider;

/**
 * Edition service provider to set global stuff.
 */
class EditionServiceProvider extends ServiceProvider
{
    /**
     * Override the service provider.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->runningInConsole()) {
            if (APP_EDITION == 'bdgo') {
                $customerType = Helper::getCustomerTypeTenant();

                if (empty($customerType)) {
                    $customerType = Helper::getCustomerTypeCustomer();
                }

                // Update config.
                config(['bdgo.customer_type' => $customerType]);
                config(['bdgo.customer_type_id' => Helper::getCustomerTypeId()]);
            }
        }
    }
}
