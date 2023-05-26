<?php

namespace App\Helpers;

use App\Jobs\ComputeDashboardWidgetsJob;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Livetrack;
use App\Models\Bdgo\CustomerType;
use Config;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Tenancy\Facades\Tenancy;

class Helper
{
    public static function appClasses()
    {

        $data = config('custom.custom');

        // default data array
        $DefaultData = [
            'myLayout' => 'vertical',
            'myTheme' => 'theme-default',
            'myStyle' => 'light',
            'myRTLSupport' => true,
            'myRTLMode' => true,
            'hasCustomizer' => true,
            'showDropdownOnHover' => true,
            'displayCustomizer' => true,
            'menuFixed' => true,
            'menuCollapsed' => false,
            'navbarFixed' => true,
            'footerFixed' => false,
            'customizerControls' => [
                'rtl',
                'style',
                'layoutType',
                'showDropdownOnHover',
                'layoutNavbarFixed',
                'layoutFooterFixed',
                'themes',
            ],
            'defaultLanguage'=>'de',
            'bodyCustomClass' => '', //any custom class can be pass
        ];

        // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
        $data = array_merge($DefaultData, $data);


        // All options available in the template
        $allOptions = [
            'myLayout' => ['vertical', 'horizontal', 'blank'],
            'menuCollapsed' => [true, false],
            'hasCustomizer' => [true, false],
            'showDropdownOnHover' => [true, false],
            'displayCustomizer' => [true, false],
            'myStyle' => ['light', 'dark'],
            'myTheme' => ['theme-default', 'theme-bordered', 'theme-semi-dark'],
            'myRTLSupport' => [true, false],
            'myRTLMode' => [true, false],
            'menuFixed' => [true, false],
            'navbarFixed' => [true, false],
            'footerFixed' => [true, false],
            'customizerControls' => [],
            'defaultLanguage'=>array('en'=>'en','fr'=>'fr','de'=>'de','pt'=>'pt'),
        ];

        //if myLayout value empty or not match with default options in custom.php config file then set a default value
        foreach ($allOptions as $key => $value) {
            if (array_key_exists($key, $DefaultData)) {
                if (gettype($DefaultData[$key]) === gettype($data[$key])) {
                    // data key should be string
                    if (is_string($data[$key])) {
                        // data key should not be empty
                        if (isset($data[$key]) && $data[$key] !== null) {
                            // data key should not be exist inside allOptions array's sub array
                            if (!array_key_exists($data[$key], $value)) {
                                // ensure that passed value should be match with any of allOptions array value
                                $result = array_search($data[$key], $value, 'strict');
                                if (empty($result) && $result !== 0) {
                                    $data[$key] = $DefaultData[$key];
                                }
                            }
                        } else {
                            // if data key not set or
                            $data[$key] = $DefaultData[$key];
                        }
                    }
                } else {
                    $data[$key] = $DefaultData[$key];
                }
            }
        }
        //layout classes
        $layoutClasses = [
            'layout' => $data['myLayout'],
            'theme' => $data['myTheme'],
            'style' => $data['myStyle'],
            'rtlSupport' => $data['myRTLSupport'],
            'rtlMode' => $data['myRTLMode'],
            'textDirection' => $data['myRTLMode'],
            'menuCollapsed' => $data['menuCollapsed'],
            'hasCustomizer' => $data['hasCustomizer'],
            'showDropdownOnHover' => $data['showDropdownOnHover'],
            'displayCustomizer' => $data['displayCustomizer'],
            'menuFixed' => $data['menuFixed'],
            'navbarFixed' => $data['navbarFixed'],
            'footerFixed' => $data['footerFixed'],
            'customizerControls' => $data['customizerControls'],
            'defaultLanguage' => $data['defaultLanguage'],
            'bodyCustomClass' => $data['bodyCustomClass'],
        ];

        // sidebar Collapsed
        if ($layoutClasses['menuCollapsed'] == true) {
            $layoutClasses['menuCollapsed'] = 'layout-menu-collapsed';
        }

        // Menu Fixed
        if ($layoutClasses['menuFixed'] == true) {
            $layoutClasses['menuFixed'] = 'layout-menu-fixed';
        }

        // Navbar Fixed
        if ($layoutClasses['navbarFixed'] == true) {
            $layoutClasses['navbarFixed'] = 'layout-navbar-fixed';
        }

        // Footer Fixed
        if ($layoutClasses['footerFixed'] == true) {
            $layoutClasses['footerFixed'] = 'layout-footer-fixed';
        }

        // RTL Supported template
        if ($layoutClasses['rtlSupport'] == true) {
            $layoutClasses['rtlSupport'] = '/rtl';
        }

        // RTL Layout/Mode
        if ($layoutClasses['rtlMode'] == true) {
            $layoutClasses['rtlMode'] = 'rtl';
            $layoutClasses['textDirection'] = 'rtl';
        } else {
            $layoutClasses['rtlMode'] = 'ltr';
            $layoutClasses['textDirection'] = 'ltr';
        }

        // Show DropdownOnHover for Horizontal Menu
        if ($layoutClasses['showDropdownOnHover'] == true) {
            $layoutClasses['showDropdownOnHover'] = 'true';
        } else {
            $layoutClasses['showDropdownOnHover'] = 'false';
        }

        // To hide/show display customizer UI, not js
        if ($layoutClasses['displayCustomizer'] == true) {
            $layoutClasses['displayCustomizer'] = 'true';
        } else {
            $layoutClasses['displayCustomizer'] = 'false';
        }

        return $layoutClasses;
    }

    public static function applClassesOld()
    {
        // default data value
        $dataDefault = [
          'mainLayoutType' => 'vertical-menu',
          'theme' => 'semi-dark',
          'isContentSidebar'=> false,
          'pageHeader' => false,
          'bodyCustomClass' => '',
          'navbarBgColor' => 'bg-white',
          'navbarType' => 'fixed',
          'isMenuCollapsed' => false,
          'footerType' => 'hidden',
          'templateTitle' => '',
          'isCustomizer' => true,
          'isCardShadow' => false,
          'isScrollTop' => false,
          'defaultLanguage' => 'de',
          'direction' => env('MIX_CONTENT_DIRECTION', 'ltr'),
        ];

        //if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
        $data = array_merge($dataDefault, config('custom.custom'));

        // $fullURL = request()->fullurl();
        // $data = [];
        // if (App()->environment() === "production") {
        //     for ($i = 1; $i < 7; $i++) {
        //         $contains = Str::contains($fullURL, "demo-" . $i);
        //         if ($contains === true) {
        //             $data = config("demo-".$i.".custom");
        //         }
        //     }
        // }
        // $data = array_merge($dataDefault, $data);

        // all available option of materialize template
        $allOptions = [
          'mainLayoutType' => array('vertical-menu','horizontal-menu','vertical-menu-boxicons'),
          'theme' => array('light'=>'light','dark'=>'dark','semi-dark'=>'semi-dark'),
          'isContentSidebar'=> array(false,true),
          'pageHeader' => array(false,true),
          'bodyCustomClass' => '',
          'navbarBgColor' => array('bg-white','bg-primary', 'bg-success','bg-danger','bg-info','bg-warning','bg-dark'),
          'navbarType' => array('fixed'=>'fixed','static'=>'static','hidden'=>'hidden'),
          'isMenuCollapsed' => array(false,true),
          'footerType' => array('fixed'=>'fixed','static'=>'static','hidden'=>'hidden'),
          'templateTitle' => '',
          'isCustomizer' => array(true,false),
          'isCardShadow' => array(true,false),
          'isScrollTop' => array(true,false),
          'defaultLanguage'=>array('en' => 'en','pt' => 'pt','fr' => 'fr','de' => 'de'),
          'direction' => array('ltr' => 'ltr','rtl' => 'rtl'),
        ];
        // navbar body class array
        $navbarBodyClass = [
          'fixed'=>'navbar-sticky',
          'static'=>'navbar-static',
          'hidden'=>'navbar-hidden',
        ];
        $navbarClass  = [
          'fixed'=>'fixed-top',
          'static'=>'navbar-static-top',
          'hidden'=>'d-none',
        ];
        // footer class
        $footerBodyClass = [
          'fixed'=>'fixed-footer',
          'static'=>'footer-static',
          'hidden'=>'footer-hidden',
        ];
        $footerClass = [
          'fixed'=>'footer-sticky',
          'static'=>'footer-static',
          'hidden'=>'d-none',
        ];

        //if any options value empty or wrong in custom.php config file then set a default value
        foreach ($allOptions as $key => $value) {
          if (gettype($data[$key]) === gettype($dataDefault[$key])) {
            if (is_string($data[$key])) {
              if(is_array($value)){

                $result = array_search($data[$key], $value);
                if (empty($result)) {
                  $data[$key] = $dataDefault[$key];
                }
              }
            }
          } else {
            if (is_string($dataDefault[$key])) {
              $data[$key] = $dataDefault[$key];
            } elseif (is_bool($dataDefault[$key])) {
              $data[$key] = $dataDefault[$key];
            } elseif (is_null($dataDefault[$key])) {
              is_string($data[$key]) ? $data[$key] = $dataDefault[$key] : '';
            }
          }
        }

        //  above arrary override through dynamic data
        $layoutClasses = [
          'mainLayoutType' => $data['mainLayoutType'],
          'theme' => $data['theme'],
          'isContentSidebar'=> $data['isContentSidebar'],
          'pageHeader' => $data['pageHeader'],
          'bodyCustomClass' => $data['bodyCustomClass'],
          'navbarBgColor' => $data['navbarBgColor'],
          'navbarType' => $navbarBodyClass[$data['navbarType']],
          'navbarClass' => $navbarClass[$data['navbarType']],
          'isMenuCollapsed' => $data['isMenuCollapsed'],
          'footerType' => $footerBodyClass[$data['footerType']],
          'footerClass' => $footerClass[$data['footerType']],
          'templateTitle' => $data['templateTitle'],
          'isCustomizer' => $data['isCustomizer'],
          'isCardShadow' => $data['isCardShadow'],
          'isScrollTop' => $data['isScrollTop'],
          'defaultLanguage' => $data['defaultLanguage'],
          'direction' => $data['direction'],
        ];

         // set default language if session hasn't locale value the set default language
         if(!session()->has('locale')){
            app()->setLocale($layoutClasses['defaultLanguage']);
          }

        return $layoutClasses;
    }
    // updatesPageConfig function override all configuration of custom.php file as page requirements.
    public static function updatePageConfig($pageConfigs)
    {
        $demo = 'custom';
        $custom = 'custom';
        // $fullURL = request()->fullurl();
        // if(App()->environment() === 'production'){
        //     for ($i=1; $i < 7; $i++) {
        //         $contains = Str::contains($fullURL, 'demo-'.$i);
        //         if($contains === true){
        //             $demo = 'demo-'.$i;
        //         }
        //     }
        // }
        if (isset($pageConfigs)) {
            if (count($pageConfigs) > 0) {
                foreach ($pageConfigs as $config => $val) {
                    Config::set($demo . '.' . $custom . '.' . $config, $val);
                }
            }
        }
    }

    /**
     * Shorten the given number in k,m,b or t style.
     *
     * @param $number
     * @return false|string
     */
    public static function numberFormatShort($number) {
        $number = (0+str_replace(",", "", $number));
        if (!is_numeric($number)) return false;
        if ($number > 1000000000000) return round(($number/1000000000000), 2).'T';
        elseif ($number > 1000000000) return round(($number/1000000000), 2).'B';
        elseif ($number > 1000000) return round(($number/1000000), 2).'M';
        elseif ($number > 1000) return round(($number/1000), 2).'K';

        return number_format($number);
    }

    /**
     * Convert the given input to minute(s).
     *
     * @param $string
     * @return int|string
     */
    public static function convertToMinutes($string)
    {
        $inMunites = $string;

        if (!empty($string)) {
            if (str_contains($string, ',')) {
                $time = explode(',', $string);
            } else if (str_contains($string, ':')) {
                $time = explode(':', $string);
            }

            $hours   = (!empty($time[0])) ? (int)$time[0] : 0;
            $minutes = (!empty($time[1])) ? (int)$time[1] : 0;

            $inMunites = ($hours * 60) + $minutes;
        }

        return $inMunites;
    }

    /**
     * Format the given input to hours:minutes or in given style.
     *
     * @param $time
     * @param string $format
     * @return int|string
     */
    public static function formatHoursAndMinutes($time, $format = '%02d,%02d', $overwrite_format = false)
    {
        if ($time < 1 && !str_contains($time,':')) {
            return 0;
        }

        if(str_contains($time,',')){
            // time in hours,minutes format
            $time = explode(',',$time);
            $minutes = ($time[1] % 60);
            $hours = $time[1] > 0 ? floor($time[1] / 60) + $time[0] : 0 + $time[0];
        } elseif (str_contains($time,':')){
            // time in hours:minutes format
            $time = explode(':',$time);
            $minutes = ($time[1] % 60);
            $hours = $time[1] > 0 ? floor($time[1] / 60) + $time[0] : 0 + $time[0];
        } else {
            // time in minutes
            $hours = $minutes = $time;
            $hours = floor($hours / 60);
            $minutes = ($minutes % 60);
        }
        if(!$overwrite_format){
            $format = $hours > 99 && $format == '%02d:%02dh' ? '%0'.strlen($hours).'d:%02dh' : '%02d:%02dh';
        }
        return sprintf($format, $hours, $minutes);
    }

    /**
     * Get percentage of give value
     *
     * @param $value
     * @param int $max
     */
    public static function valueToPercent($value, int $max = 1000)
    {
        return $value ? round((($value * 100) / $max), 2) : 0;
    }

    /**
     * Get percentage of value given for input array
     *
     * @param array $data
     */
    public static function convertArrayValueToPercentage($data): array
    {
        $totalValue = (!empty($data) && is_array($data)) ? array_sum($data) : 0;

        return array_map(fn($key, $value) => Helper::valueToPercent($value, $totalValue),
            array_keys($data),
            array_values($data)
        );
    }

    public static function getFormattedDays($days)
    {
        $daysData = [];
        $daysString = [];
        foreach ($days as $day => $bool) {
            if ($bool == true) {
                $daysData[] = $day;
            } else if($counts = count($daysData)) {
                $daysString[] = ($counts > 1) ? self::getShortTranslationForDay(head($daysData)).'-'.self::getShortTranslationForDay(last($daysData)) : self::getShortTranslationForDay(head($daysData));
                $daysData = [];
            }
        }
        $daysString[] = (count($daysData) > 1) ? self::getShortTranslationForDay(head($daysData)).'-'.self::getShortTranslationForDay(last($daysData)) : self::getShortTranslationForDay(head($daysData));
        return $daysString;
    }

    public static function getShortTranslationForDay($day)
    {
        switch ($day) {
            case('monday'):
                return __('locale.Mo');
            case('tuesday'):
                return __('locale.Tu');
            case('wednesday'):
                return __('locale.We');
            case('thursday'):
                return __('locale.Th');
            case('friday'):
                return __('locale.Fr');
            case('saturday'):
                return __('locale.Sa');
            case('sunday'):
                return __('locale.Su');
        }
    }

    /**
     * Computes and Store All Dashboard Widgets
     * @return void
     */
    public static function computeAndStoreAllDashboardWidgets() : void
    {
        $tenant = Tenancy::getTenant();

        if (!empty($tenant)) {
            $lock = Cache::lock('computeDashboardIndex-' . $tenant->id, 120);

            if ($lock->get()) {
                $dashboardJob = new ComputeDashboardWidgetsJob($tenant);

                $dashboardJob->handle();
            }
        }
    }

    /**
     * Convert minutes to hours and minutes.
     * e.g. 150 mins convert to 02:30
     *
     * @param $minutes (int)
     * @param $separator(optional) Default ":"
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return string
     */
    public static function convertMinsToHoursMins($minutes, $separator = ":")
    {
        // Convert to hours and minutes.
        $convertedHours   = intdiv($minutes, 60);
        $convertedMinutes = ($minutes % 60);

        // Pad zero for single character.
        if (strlen($convertedHours) == 1) {
            $convertedHours = str_pad($convertedHours, 2, "0", STR_PAD_LEFT);
        }
        if (strlen($convertedMinutes) == 1) {
            $convertedMinutes = str_pad($convertedMinutes, 2, "0", STR_PAD_LEFT);
        }

        // Add separator and return new values.
        return ($convertedHours . $separator . $convertedMinutes);
    }

    /**
     * Check is admin or tenant.
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return Bool
     */
    public static function isAdmin():bool
    {
        return (!empty(auth('web')->user()));
    }

    /**
     * Get random number.
     *
     * @param $digits(optional) Default "5"
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return String
     */
    public static function getRandomNumber(int $digits = 5)
    {
        return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    }

    /**
     * Get selected customer ID from cookie.
     * This method used for bdgo.
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return Integer|Null
     */
    public static function getSelectedCustomerId()
    {
        $cookieCustomerId = request()->cookie('customer_id');

        return (!empty($cookieCustomerId)) ? Crypt::decrypt($cookieCustomerId) : '';
    }

    /**
     * Get customer type ID from Tenant (Company).
     * This method used for bdgo.
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return Integer|Null
     */
    public static function getCustomerTypeIdTenant()
    {
        $tenant = Tenancy::getTenant();

        return (!empty($tenant) && !empty($tenant->customer_type_id)) ? $tenant->customer_type_id : null;
    }

    /**
     * Get customer type ID from Customer model.
     * This method used for bdgo.
     *
     * @param $customerId int
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return Integer|Null
     */
    public static function getCustomerTypeIdFromModel(int $customerId)
    {
        $customer = Customer::find($customerId);

        if (!empty($customer)) {
            return $customer->customer_type_id;
        }

        return null;
    }

    /**
     * Get customer type ID from Customer.
     * This method used for bdgo.
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return Integer|Null
     */
    public static function getCustomerTypeIdCustomer()
    {
        $customerTypeId = null;

        $customerId     = self::getSelectedCustomerId();

        if (!empty($customerId)) {
            $customerTypeId = self::getCustomerTypeIdFromModel((int)$customerId);
        }

        return $customerTypeId;
    }

    /**
     * Get customer type ID.
     * First check for Tenant and then check for a company if $isFromCustomer = true.
     * This method used for bdgo.
     *
     * @param $isFromCustomer Boolean
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return Integer|Null
     */
    public static function getCustomerTypeId($isFromCustomer = true)
    {
        $customerType = self::getCustomerTypeIdTenant();

        if ($isFromCustomer && empty($customerType)) {
            $customerType = self::getCustomerTypeIdCustomer();
        }

        return $customerType;
    }

    /**
     * Get language filr name by using customer type.
     * This method used for bdgo.
     *
     * @param $getCustomerType Integer
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return String|Null
     */
    public static function getCustomerTypeLanguageFileName($getCustomerType)
    {
        $customerType = null;

        switch ($getCustomerType) {
            case("0"):
                $customerType = 'all';

                break;
            case("1"):
                $customerType = 'church';

                break;
            case("2"):
                $customerType = 'sme';

                break;
            case("3"):
                $customerType = 'school';

                break;
            case("4"):
                $customerType = 'authorities';

                break;
            case("5"):
                $customerType = 'association';

                break;
            case("6"):
                $customerType = 'health_care';

                break;
            case("7"):
                $customerType = 'medical_care';

                break;
        }

        return $customerType;
    }

    /**
     * Get customer type for Tenant.
     * This method used for bdgo.
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return String|Null
     */
    public static function getCustomerTypeTenant()
    {
        $tenant         = Tenancy::getTenant();

        $customerType   = (!empty($tenant)) ? $tenant->customer_type : null;

        return  $customerType;
    }

    /**
     * Get customer type for Customer.
     * This method used for bdgo.
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return String|Null
     */
    public static function getCustomerTypeCustomer()
    {
        $customerType    = null;

        $customerTypeId  = self::getCustomerTypeIdCustomer();

        // Find customer type.
        $getCustomerType = CustomerType::find($customerTypeId);

        if (!empty($getCustomerType)) {
            $customerType = self::getCustomerTypeLanguageFileName($getCustomerType->type);
        }

        return $customerType;
    }

    /**
     * Table footer string of total records.
     *
     * @param $currentPage integer Default:1
     * @param $perPage integer Default:10
     * @param $total integer Default:0
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return String|Null
     */
    public static function tableFooterString($currentPage = 1, $perPage = 10, $total = 0)
    {
        $from = (($currentPage -1) * $perPage + 1);
        $to   = ($currentPage * $perPage);

        // Fix if $to greater than $total
        if ($to > $total) {
            $to = $total;
        }

        return $from . ' ' . __('locale.To') . ' ' . $to . ' ' . __('locale.From') . ' ' . $total . ' ' . __('locale.Entries');
    }

    /**
     * Parse months to year and month.
     * For e.g. if 120 then 10 Years, 125 then 10 Years 5 Months, 13 then 1 Year 1 Month.
     *
     * @param $month integer
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return String
     */
    public static function parseMonthToYear(int $month)
    {
        if ($month > 0) {
            $parsedString   = NULL;
            $totalMonth     = 12;
            $divisible      = ($month / $totalMonth % $totalMonth);
            $remainder      = ($month - ($totalMonth * $divisible));

            // Set year string.
            if ($divisible > 0) {
                if ($divisible === 1) {
                    $yearString = __('locale.Year');
                } elseif ($divisible > 1) {
                    $yearString = __('locale.Years');
                }

                $parsedString = $divisible . " " . $yearString;
            }

            // Set month string.
            if ($remainder > 0) {
                if ($remainder === 1) {
                    $monthString = __('locale.Month');
                } elseif ($remainder > 1) {
                    $monthString = __('locale.Months');
                }

                $parsedString .= " " . $remainder . " " . $monthString;
            }

            return $parsedString;
        }

        return $month;
    }

    /**
     * Get current theme of site.
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return String|Null
     */
    public static function getCurrentTheme()
    {
        $currentTheme = request()->cookie('current-theme', '');

        return (!empty($currentTheme)) ? Crypt::decrypt($currentTheme) : (!empty(config('custom.custom.myStyle', '')) ? config('custom.custom.myStyle', '') : null);
    }

    /**
     * Convert minutes to hours and minutes.
     *
     * @param $minutes Integer minutes
     * @param $separator String Default: ":"
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return String|Null
     */
    public static function inHoursAndMinutes($minutes, $separator = " ")
    {
        $hour = 'h';
        $minute = 'm';

        // Convert to hours and minutes.
        $convertedHours   = intdiv($minutes, 60);
        $convertedMinutes = ($minutes % 60);

        // Add separator and return new values.
        return ((($convertedHours > 0) ? $convertedHours . $hour : null) . (($convertedMinutes > 0) ? (($convertedHours > 0) ? $separator : '') . $convertedMinutes . $minute : null));
    }

    /**
     * Check current loggedin user has livetracker running or not.
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return Boolean
     */
    public static function isLivetrackRunning():bool
    {
        return Livetrack::where('user_id', auth()->id())->where('end_date', null)->exists();
    }

    /**
     * Sort multi-dimentional array by it's value.
     *
     * @param array $array
     * @param string $
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return Array
     */
    public static function sortArrayByValue(array $array, string $sortBy):array
    {
        return collect($array)->sortBy($sortBy)->reverse()->toArray();
    }

    /**
     * Check dashboard widgets queues batch running or not.
     *
     * @param string $batchId
     *
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     *
     * @return Object|Null
     */
    public static function checkDashboardRunningBatch(string $batchId)
    {
        return Bus::findBatch($batchId);
    }
}
