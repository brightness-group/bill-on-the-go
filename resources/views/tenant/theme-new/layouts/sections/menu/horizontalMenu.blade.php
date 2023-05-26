@php
    $configData = Helper::appClasses();
@endphp
<!-- Horizontal Menu -->
<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal  menu bg-menu-theme flex-grow-0">
    <!-- ! Hide app brand if navbar-full -->
    @if(!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link">
                <span class="app-brand-logo">
                    @if ($tenant->logo)
                        <img src="{{ url($tenant->logo) }}" class="logo" style="width:170px;height:30px;" alt="">
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

    @if (config('app.app_edition') == 'Bdgo')
        <div class="card-body top-drop py-0 d">
            <div class="mt-2">
                <label for="customer-select" class="form-label d-flex">
                    <strong class="mt-2">{{ __('locale.Customers') }}&nbsp;:&nbsp;</strong>

                    @php
                        $cookieName = 'customer_id';

                        $customers  = App\Models\Tenant\Customer::select('id', 'customer_name')->get();

                        $selectedCustomer = Helper::getSelectedCustomerId();
                    @endphp

                    <select id="customer-select" class="form-select" data-set-cookie-route="{{ route('set-cookie-ajax') }}" data-cookie-name="{{ $cookieName }}">
                        <option value="">{{ __('locale.Select Customer') }}</option>

                        @if (!empty($customers) && !$customers->isEmpty())
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ ($selectedCustomer == $customer->id) ? 'selected="true"' : '' }}>{{ $customer->customer_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </label>
            </div>
        </div>

        {{-- <div class="menu-inner-shadow"></div> --}}

        <br />
    @endif

    <div class="{{$containerNav}} d-flex h-100">
        <ul class="menu-inner">
            @foreach ($menuData[1]->menu as $menu)

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
                        <li class="menu-item text-uppercase">
                            <span class="menu-header-text">
                                @if (!empty($menu->url))
                                    <a href="{{ $menu->url }}" class="menu-link">
                                        {{ __('locale.'. $menu->menuHeader) }}
                                    </a>
                                @else
                                    <a href="javascript:void();" class="menu-link">
                                        {{ __('locale.'. $menu->menuHeader) }}
                                    </a>
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
                                        $activeClass = 'active';
                                    }
                                }
                            } elseif (!empty($menu->slug) && gettype($menu->slug) === 'array') {
                                foreach ($menu->slug as $slug) {
                                    if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                                        $activeClass = 'active';
                                    }
                                }
                            } else {
                                if (
                                    (!empty($menu->url) && str_contains($currentRouteName, $menu->url) && strpos($currentRouteName, $menu->url) === 0) ||
                                    (!empty($menu->slug) && str_contains($currentRouteName, $menu->slug) && strpos($currentRouteName, $menu->slug) === 0)
                                ) {
                                    $activeClass = 'active';
                                }
                            }
                        } elseif (!empty($menu->slug) && gettype($menu->slug) === 'array') {
                            foreach ($menu->slug as $slug) {
                                if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                                    $activeClass = 'active ';
                                }
                            }
                        }
                    @endphp

                    {{-- main menu --}}
                    @if (empty($menu->menuHeader))
                        <li class="menu-item {{$activeClass}}">
                            <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                                @isset($menu->icon)
                                    <i class="menu-icon tf-icons bx {{ $menu->icon }}"></i>
                                @endisset
                                <div>
                                    {{ isset($menu->name) ? __('locale.'.$menu->name) : '' }}
                                </div>
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
    </div>
</aside>
<!--/ Horizontal Menu -->
