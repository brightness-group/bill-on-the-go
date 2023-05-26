<!DOCTYPE html>
<!--
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

{{--<html class="loading" lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif"--}}
{{-- data-textdirection="{{$configData['direction'] == 'rtl' ? 'rtl' : 'ltr' }}">--}}

<html lang="{{ session()->get('locale') ?? $configData['defaultLanguage'] }}"
      class="{{ $configData['style'] }}-style {{ $navbarFixed ?? '' }}
      {{ $menuFixed ?? '' }}
      {{ $menuCollapsed ?? '' }}
      {{ $footerFixed ?? '' }}
      {{ $customizerHidden ?? '' }}"
      dir="{{ $configData['textDirection'] }}"
      data-theme="{{ (($configData['theme'] === 'theme-semi-dark')
? (($configData['layout'] !== 'horizontal')
? $configData['theme'] : 'theme-default')
:  $configData['theme']) }}"
      data-assets-path="{{ asset('/assets') . '/' }}"
      data-base-url="{{url('/')}}"
      data-framework="laravel"
      data-template="{{ $configData['layout'] . '-menu-' . $configData['theme'] . '-' . $configData['style'] }}">

  <!-- BEGIN: Head-->

    <head>
    <meta  charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $tenant }} - @yield('title')</title>
    <link rel="apple-touch-icon" href="{{asset('images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/ico/favicon-32x32.png')}}">
    <link rel="stylesheet" href="{{ asset('vendors/css/extensions/sweetalert2.min.css') }}">

        {{dd('$tenant =>', $tenant)}}

    {{-- Include core + vendor Styles --}}
    @include('tenant.panels.styles')
    @yield("custom_css")
    @stack("styles")
    @stack("scripts")
    @livewireStyles
    </head>
    <!-- END: Head-->
     @livewire('spinner-window-component')
{{--     <div class="timer-component-style rounded {{ request()->url() }}  d-flex justify-content-center align-items-center" style="position: fixed;bottom: 10%;right: 60px;z-index: 1031;width: 200px;">--}}
     @livewire('tenant.partials.timer-nav-database-component')
{{--     </div>--}}
     @livewire('tenant.partials.nested-component.connection-report-recovery-data-for-swall')
     @if(!empty($configData['layout']) && isset($configData['layout']))
     @include(($configData['layout'] === 'horizontal-menu') ? 'tenant.layouts.horizontalLayoutMaster':'tenant.layouts.verticalLayoutMaster')
     @else
     {{-- if mainLaoutType is empty or not set then its print below line --}}
     <h1>{{'myLayout Option is empty in config custom.php file.'}}</h1>
     @endif


  {{-- livewire Scripts --}}
  @livewireScripts

  @yield('custom_scripts')
  <script src="{{ asset('vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
  <script>
        window.onload = function() {
            Livewire.hook('message.sent', (message,component) => {
                if (message.updateQueue[0].method === 'retrieveFromAPI' || message.updateQueue[0].payload.event === 'retrieveFromAPIEmitedEvent'
                    || message.updateQueue[0].payload.event === 'retrieveFromAPIEmittedEvent')
                    Livewire.emitTo('spinner-window-component', 'openSpinnerWindow', 'Please wait...');
            });
            Livewire.hook('message.processed', (message, component) => {
                if (message.updateQueue[0].method === 'retrieveFromAPI' || message.updateQueue[0].payload.event === 'retrieveFromAPIEmitedEvent'
                    || message.updateQueue[0].payload.event === 'retrieveFromAPIEmittedEvent')
                    Livewire.emit('closeSpinnerWindow');
                else if (message.updateQueue[0].method === 'stopChronosTimerComponent') {
                    window.livewire.emitTo('tenant.partials.timer-nav-database-component', 'stopChronos');
                }
            });
            Livewire.emit('check_for_recovery_connection');
        };
        $(document).ready(function() {

            window.addEventListener('swallRecoveryData', event => {
                Swal.fire({
                    title: "{{ __('locale.Recovery data') }}",
                    text: "{{ __('locale.proceed connection recovery swall') }}",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('locale.Yes, do it!') }}",
                    cancelButtonText: "{{ __('locale.Cancel') }}",
                }).then((result) => {
                    if (result.value) {
                        Livewire.emit('process_recovery_connection');
                    }
                    else if (result.dismiss) {
                        Livewire.emit('destroy_recovery_connection_session');
                    }
                })
            });

            toastr.options = {
                positionClass: 'toast-top-center',
                showDuration: 1000,
                timeOut: 3000,
                hideDuration: 2000,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut',
                preventDuplicates: false,
            }
            window.addEventListener('toastrTVRetrieveSuccess', event => {
                toastr.success('',event.detail.message).css("width","fit-content")
                feather.replace();
            })
            window.addEventListener('toastrTVRetrieveError', event => {
                toastr.error('',event.detail.message).css("width","fit-content")
            });
        });


  </script>


{{--  <script>--}}
{{--      var idleMax = {{ config('session.lifetime') }};--}}
{{--      var idleTime = 0;--}}
{{--      var idleInterval;--}}
{{--      var tenseconds = 0;--}}
{{--      $(document).ready(function () {--}}
{{--          idleInterval = setInterval("timerIncrement()", 10000);--}}
{{--          $('*').bind('mousemove keydown scroll', function () {--}}
{{--              idleTime = 0;--}}
{{--              tenseconds = 0;--}}
{{--              clearInterval(idleInterval);--}}
{{--              idleInterval = setInterval("timerIncrement()", 10000);--}}
{{--          });--}}
{{--          $("body").trigger("mousemove");--}}
{{--      });--}}
{{--      function timerIncrement() {--}}
{{--          tenseconds += 10;--}}
{{--          idleTime += 10000;--}}
{{--          if (tenseconds >= 60) {--}}
{{--              tenseconds=0;--}}
{{--          }--}}
{{--          console.log(tenseconds,idleTime);--}}
{{--          if (idleTime >= (idleMax * 60000) - 10000) {--}}
{{--              Livewire.emit('store_timer_data_on_timeout');--}}
{{--              clearInterval(idleInterval);--}}
{{--          }--}}
{{--      }--}}
{{--  </script>--}}

</html>
