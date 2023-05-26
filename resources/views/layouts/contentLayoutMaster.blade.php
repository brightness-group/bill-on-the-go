<!DOCTYPE html>
<!-- jsusas
Template Name: Frest HTML Admin Template
Author: :Pixinvent
Website: http://www.pixinvent.com/
Contact: hello@pixinvent.com
Follow: www.twitter.com/pixinvents
Like: www.facebook.com/pixinvents
Purchase: https://1.envato.market/pixinvent_portfolio
Renew Support: https://1.envato.market/pixinvent_portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
Probar si es este
-->
{{-- pageConfigs variable pass to Helper's updatePageConfig function to update page configuration  --}}
@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
// confiData variable layoutClasses array in Helper.php file.
  $configData = Helper::applClasses();
@endphp

{{--
<html class="loading" lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif"
 data-textdirection="{{$configData['direction'] == 'rtl' ? 'rtl' : 'ltr' }}">
--}}

<html lang="{{ session()->get('locale') ?? $configData['defaultLanguage'] }}" class="{{ $configData['style'] }}-style {{ $navbarFixed ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}" dir="{{ $configData['textDirection'] }}" data-theme="{{ (($configData['theme'] === 'theme-semi-dark') ? (($configData['layout'] !== 'horizontal') ? $configData['theme'] : 'theme-default') :  $configData['theme']) }}" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="{{ $configData['layout'] . '-menu-' . $configData['theme'] . '-' . $configData['style'] }}">

  <!-- BEGIN: Head-->

    <head>
    <meta  charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - @isset($title) {{ __('locale.'.$title) }} @else @yield('title') @endisset</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/ico/favicon-32x32.png')}}">

    {{-- Include core + vendor Styles --}}
    @include('panels.styles')
    @yield('custom_css')
        @livewireStyles
    </head>
    <!-- END: Head-->

     @if(!empty($configData['myLayout']) && isset($configData['myLayout']))
     @include(($configData['myLayout'] === 'horizontal-menu') ? 'layouts.horizontalLayoutMaster':'layouts.verticalLayoutMaster')
     @else
     {{-- if mainLaoutType is empty or not set then its print below line --}}
     <h1>{{'mainLayoutType Option is empty in config custom.php file.'}}</h1>
     @endif

{{--     @yield('content')--}}

  {{-- livewire Scripts --}}
  @livewireScripts
  <!-- BEGIN: Custom Scripts-->

  @yield('custom_scripts')

</html>
