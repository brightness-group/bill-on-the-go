@php
    $configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    @if(!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link">
                <span class="app-brand-logo">
                    @if ($tenant->logo || $tenant->rectangle_logo)
                        @if ($tenant->rectangle_logo)
                            <img
                                src="{{ url(\App\Helpers\CoreHelpers::getFileUrl($tenant->rectangle_logo, 'rectangle')) }}"
                                class="logo brand-rectangle-logo" style="width:170px;height:30px;" alt="">
                        @endif
                        @if ($tenant->logo)
                            <img src="{{ url(\App\Helpers\CoreHelpers::getFileUrl($tenant->logo, 'square')) }}"
                                 class="logo brand-square-logo" style="width:30px;height:30px;" alt="">
                        @endif
                    @else
                        <i class="bx bx-buildings bx-lg"></i>
                    @endif
                </span>
                <span class="app-brand-text demo menu-text fw-bold ms-2">{{config('variables.templateName')}}</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
                <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
            </a>
        </div>
    @endif

    <!-- ! Hide menu divider if navbar-full -->
    <!-- @if(!isset($navbarFull))
        <div class="menu-divider mt-0 ">
        </div>
    @endif -->

    @if (config('app.app_edition') == 'Bdgo')
        <div class="card-body top-drop py-0 d">
            <div class="mt-1">
                <label for="customer-select" class="form-label"> <strong>  {{ __('locale.Customers') }}  </strong></label>

                @php
                    $cookieName = 'customer_id';

                    $customers  = App\Models\Tenant\Customer::select('id', 'customer_name')->get();

                    $selectedCustomer = Helper::getSelectedCustomerId();
                @endphp

                <select id="customer-select" class="form-select form-select-lg" data-set-cookie-route="{{ route('set-cookie-ajax') }}" data-cookie-name="{{ $cookieName }}">
                    <option value="">{{ __('locale.Select Customer') }}</option>

                    @if (!empty($customers) && !$customers->isEmpty())
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ ($selectedCustomer == $customer->id) ? 'selected="true"' : '' }}>{{ $customer->customer_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        {{-- <div class="menu-inner-shadow"></div> --}}

        <!-- <br /> -->
    @endif

    <ul class="menu-inner py-1">
        @foreach ($menuData[0]->menu as $menu)

            {{-- adding active and open class if child is active --}}

            {{-- menu headers --}}
            @if (isset($menu->menuHeader) && config('app.app_edition') == 'billonthego')
                @if ($menu->menuHeader == 'tenant-name')
                    <li class="menu-header text-uppercase">
                        <span style="color: #bac0c7;box-shadow: 0 4px 8px 0 rgb(0 0 0 / 12%), 0 2px 4px 0 rgb(0 0 0 / 8%);" class="menu-header-text fs-6">
                            {{ is_object($tenant) ? $tenant->name : $tenant }}
                        </span>
                    </li>
                @else
                    {{-- Removed as per TBL-343 --}}
                    {{-- <li class="menu-header text-uppercase small">
                        <span class="menu-header-text">{{ __('locale.'.$menu->menuHeader) }}</span>
                    </li> --}}
                @endif
            @else

                @if (!empty($menu->menuHeader) && config('app.app_edition') == 'Bdgo')
                    <li class="menu-header text-uppercase">
                        <span class="menu-header-text">
                            @if (!empty($menu->url))
                                <a href="{{ $menu->url }}">
                                    {{ __('locale.'. $menu->menuHeader) }}
                                </a>
                            @else
                                {{ __('locale.'. $menu->menuHeader) }}
                            @endif
                        </span>
                    </li>
                @endif

                {{-- active menu method --}}
                @php
                    $activeClass      = null;
                    $currentRouteName = Route::currentRouteName();

                    if (
                        (!empty($menu->url) && $currentRouteName === $menu->url) ||
                        (!empty($menu->slug) && $currentRouteName === $menu->slug) ||
                        (!empty($menu->route_name) && $menu->route_name === $currentRouteName)
                    ) {
                        $activeClass = 'active';
                    } elseif (!empty($menu->submenu)) {
                        if (!empty($menu->url) && gettype($menu->url) === 'array') {
                            foreach ($menu->url as $url) {
                                if (str_contains($currentRouteName, $url) && strpos($currentRouteName, $url) === 0) {
                                    $activeClass = 'active open';
                                }
                            }
                        } elseif (!empty($menu->slug) && gettype($menu->slug) === 'array') {
                            foreach ($menu->slug as $slug) {
                                if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                                    $activeClass = 'active open';
                                }
                            }
                        } else {
                            if (
                                (!empty($menu->url) && str_contains($currentRouteName, $menu->url) && strpos($currentRouteName, $menu->url) === 0) ||
                                (!empty($menu->slug) && str_contains($currentRouteName, $menu->slug) && strpos($currentRouteName, $menu->slug) === 0)
                            ) {
                                $activeClass = 'active open';
                            }
                        }
                    } elseif (!empty($menu->slug) && gettype($menu->slug) === 'array') {
                        foreach ($menu->slug as $slug) {
                            if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                                $activeClass = 'active open';
                            }
                        }
                    }
                @endphp

                {{-- main menu --}}
                @if (empty($menu->menuHeader))
                    <li class="menu-item {{$activeClass}} {{ (config('app.app_edition') == 'Bdgo') ? 'open' : '' }}">
                        <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                        class="{{ isset($menu->submenu) ? 'menu-link' : 'menu-link' }}{{ (config('app.app_edition') == 'Bdgo') ? ' text-uppercase' : '' }}"
                        @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                            @isset($menu->icon)
                                <i class="menu-icon tf-icons bx {{ $menu->icon }}"></i>
                            @endisset
                            <div>{{ isset($menu->name) ? __('locale.'.$menu->name) : '' }}</div>
                        </a>

                        {{-- submenu --}}
                        @isset($menu->submenu)
                            @include('tenant.theme-new.layouts.sections.menu.submenu',['menu' => $menu->submenu])
                        @endisset
                    </li>
                @endif
            @endif
        @endforeach
    </ul>
    <livewire:tenant.partials.show-anydesk-sync-status wire:key="showTeamviewerSyncStatus" />

    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            @if (config('app.app_edition') == 'Bdgo')
                <span class="app-brand-logo collapsed d-none">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" class="logo" style="height:30px;" alt="">
                </span>

                <span class="app-brand-logo fixed d-none" style="padding-left: 3.5rem;">
                    <img src="{{ asset('assets/images/logo/name-logo.png') }}" class="logo" style="width:110px;height:31px;" alt="">
                </span>
            @else
                <span class="app-brand-logo collapsed d-none">
                    <img src="{{ asset('assets/images/logo/logo.svg') }}" class="logo" style="height:30px;" alt="">
                </span>

                <span class="app-brand-logo fixed d-none" style="padding-left: 1.5rem;">
                    <img src="{{ asset('assets/images/logo/name-logo.png') }}" class="logo" style="width:152px;height:30px;" alt="">
                </span>
            @endif

            <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('variables.templateName') }}</span>
        </a>
    </div>

    @if (config('app.app_edition') == 'Bdgo')
        <div class="text-center" style="margin-top: 5px;font-size: 10px;font-weight: bold;letter-spacing: 1px;cursor: auto;">
    @else
        <div class="text-center" style="margin-top: 5px;font-size: 10px;font-weight: bold;letter-spacing: 1px;color: #d0d766;cursor: auto;">
    @endif
        <span>@version('version-only')</span>
    </div>

    <div>&nbsp;</div>

</aside>
