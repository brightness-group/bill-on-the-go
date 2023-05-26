<!DOCTYPE html>

<!-- =========================================================
* Frest - Bootstrap Admin Template | v1.0.0
==============================================================

* Product Page: https://1.envato.market/frest_admin
* Created by: PIXINVENT
* Template Name: Frest - Bootstrap Admin Template
* Template Version: 1.0.0
* Author: :PIXINVENT
* Website: https://pixinvent.com
* Contact: hello@pixinvent.com
* Changelog: https://pixinvent.com/demo/frest-clean-bootstrap-admin-dashboard-template/frest-changelog.html
* Follow: www.twitter.com/pixinvents
* Like: www.facebook.com/pixinvents
* Github: https://github.com/pixinvent
* Purchase: https://1.envato.market/pixinvent_portfolio
* Renew Support: https://1.envato.market/pixinvent_portfolio
* Repository: https://github.com/pixinvent/frest-admin-dashboard-template-src
* License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
 Probar si es este

==============================================================
-->


<html lang="{{ session()->get('locale') ?? $configData['defaultLanguage'] }}" class="{{ $configData['style'] }}-style {{ $navbarFixed ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}" dir="{{ $configData['textDirection'] }}" data-theme="{{ (($configData['theme'] === 'theme-semi-dark') ? (($configData['layout'] !== 'horizontal') ? $configData['theme'] : 'theme-default') :  $configData['theme']) }}" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="{{ $configData['layout'] . '-menu-' . $configData['theme'] . '-' . $configData['style'] }}">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>

    {{-- Title --}}
    <title>{{ config('app.name') }} - @yield('title')</title>
    {{--
    <meta name="description" content="" />
    <meta name="keywords" content="">
    --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Canonical SEO --}}
    {{-- <link rel="canonical" href=""> --}}

    {{-- Favicon icons --}}
    <link rel="apple-touch-icon" href="{{asset('assets/images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/ico/favicon-32x32.png')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}"/>

    {{-- Include styles --}}
    @include('theme-new.layouts.sections.styles')

    {{-- Include scripts for customizer, helper, analytics, config --}}
    @include('theme-new.layouts.sections.scriptsIncludes')

    @yield('custom_styles')


    {{-- Livewire styles --}}
    @livewireStyles

    {{-- header scripts --}}
    @include('theme-new.layouts.sections.headScripts')
</head>

<body>
  {{-- Layout content --}}
  @yield('layoutContent')
  {{--/ Layout content --}}

  <script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>

  {{-- Include scripts --}}
  @include('theme-new.layouts.sections.scripts')

  {{-- Livewire scripts --}}
  @livewireScripts

  {{-- Custom Scripts --}}
  @yield('custom_scripts')

  {{-- laravel livewire modal --}}
  <script src="{{ asset('assets/vendor/js/modals.js') }}"></script>

  <script>
      /* general custom scripts here */
  </script>

</body>

</html>
