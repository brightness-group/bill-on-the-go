@php
    $configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    @if(!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{url('/')}}" class="app-brand-link">
                <span class="app-brand-logo">
                    <img src="{{asset('assets/images/logo/billonthego-logo.png')}}" class="logo" style="width:170px;height:30px;" alt="">
                </span>
                <span class="app-brand-text demo menu-text fw-bold ms-2">{{config('variables.templateName')}}</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
                <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
            </a>
        </div>
        <div class="text-center" style="margin-top: 5px;font-size: 10px;font-weight: bold;letter-spacing: 1px;color: #d0d766;cursor: auto;">
            <span>@version('version-only')</span>
        </div>
    @endif

<!-- ! Hide menu divider if navbar-full -->
    @if(!isset($navbarFull))
        <div class="menu-divider mt-0 ">
        </div>
    @endif

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuData[0]->menu as $menu)

            {{-- adding active and open class if child is active --}}

            {{-- menu headers --}}
            @if (isset($menu->menuHeader))
                @if ($menu->menuHeader == 'tenant-name')
                    <li class="menu-header text-uppercase">
                        <span
                            style="color: #bac0c7;box-shadow: 0 4px 8px 0 rgb(0 0 0 / 12%), 0 2px 4px 0 rgb(0 0 0 / 8%);"
                            class="menu-header-text fs-6 ">{{ is_object($tenant) ? $tenant->name : $tenant }}</span>
                    </li>
                @else
                    {{-- Removed as per TBL-343 --}}
                    {{-- <li class="menu-header text-uppercase small">
                        <span class="menu-header-text">{{ __('locale.'.$menu->menuHeader) }}</span>
                    </li> --}}
                @endif
            @else

                {{-- active menu method --}}
                @php
                    $activeClass = null;
                    $currentRouteName = Route::currentRouteName();

                    if ($currentRouteName === $menu->url || !empty($menu->route_name) && $menu->route_name === $currentRouteName) {
                        $activeClass = 'active';
                    }

                    elseif (isset($menu->submenu)) {
                        if (gettype($menu->url) === 'array') {
                            foreach($menu->url as $url){
                                if (str_contains($currentRouteName,$url) and strpos($currentRouteName,$url) === 0) {
                                    $activeClass = 'active open';
                                }
                            }
                        }
                        else{
                            if (str_contains($currentRouteName,$menu->url) and strpos($currentRouteName,$menu->url) === 0) {
                                $activeClass = 'active open';
                            }
                        }
                    }
                @endphp

                {{-- main menu --}}
                <li class="menu-item {{$activeClass}}">
                    <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                       class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                       @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                        @isset($menu->icon)
                            <i class="menu-icon tf-icons bx {{ $menu->icon }}"></i>
                        @endisset
                        <div>{{ isset($menu->name) ? __('locale.'.$menu->name) : '' }}</div>
                    </a>

                    {{-- submenu --}}
                    @isset($menu->submenu)
                        @include('theme-new.layouts.sections.menu.submenu',['menu' => $menu->submenu])
                    @endisset
                </li>
            @endif
        @endforeach
    </ul>

</aside>
