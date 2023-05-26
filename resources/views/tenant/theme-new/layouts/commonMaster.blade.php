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
    <title>{{ is_object($tenant) ? $tenant->name : $tenant }} - @yield('title')</title>
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
    @include('tenant.theme-new.layouts.sections.styles')

    {{-- Include scripts for customizer, helper, analytics, config --}}
    @include('tenant.theme-new.layouts.sections.scriptsIncludes')

    @yield('custom_styles')

    {{-- todo::Bdgo styles --}}
    @if(!empty(request()->segments()[0]) && request()->segments()[0] == 'Bdgo')
        {{-- todo::Bdgo--}}
        <link rel="stylesheet" href="{{asset('css/themes/Bdgo.css')}}">
    @endif

    {{-- Livewire styles --}}
    @livewireStyles

    {{-- header scripts --}}
    @include('tenant.theme-new.layouts.sections.headScripts')
</head>

<body>

  {{-- spinnet common component --}}
  @livewire('spinner-window-component')

  {{-- timer nav component --}}
  @livewire('tenant.partials.timer-nav-database-component')

  {{-- recover time traker data component --}}
  @livewire('tenant.partials.nested-component.connection-report-recovery-data-for-swall')

  {{-- Layout content --}}
  @yield('layoutContent')
  {{--/ Layout content --}}

  <script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>

  {{-- Include scripts --}}
  @include('tenant.theme-new.layouts.sections.scripts')

  {{-- Livewire scripts --}}
  @livewireScripts

  {{-- Custom Scripts --}}
  @yield('custom_scripts')

  {{-- laravel livewire modal --}}
  <script src="{{ asset('assets/vendor/js/modals.js') }}"></script>
  <script>
    function centerModal() {
        $(this).css('display', 'block');
        var $dialog  = $(this).find(".modal-dialog"),
            offset       = ($(window).height() - $dialog.height()) / 2,
            bottomMargin = parseInt($dialog.css('marginBottom'), 10);

        // Make sure you don't hide the top part of the modal w/ a negative margin if it's longer than the screen height, and keep the margin equal to the bottom margin of the modal
        if(offset < bottomMargin) offset = bottomMargin;
        $dialog.css("margin-top", offset);
    }

      window.onload = function () {
          Livewire.hook('message.sent', (message, component) => {
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

          Livewire.hook('element.updated', (el, component) => {
            if ($(el).hasClass('modal-dialog')) {
                $('.modal:visible').each(centerModal);
            }
          });

          Livewire.emit('check_for_recovery_connection');

          window.livewire.emitTo('tenant.partials.timer-nav-database-component', 'renderTimer');
      };
      $(document).ready(function () {

          // start time tracker from anywhere
          $(document).on('click', '.start-crono-btn', function (event) {
              let customerGroupId = $('#customers').val();
              window.livewire.emitTo('tenant.partials.timer-nav-database-component', 'startChronos', customerGroupId);
              $(this).addClass('d-none');
              $('.start-cronos-text').addClass('d-none');
              $('.start-cronos-loader').removeClass('d-none');
              $('.stop-crono-btn').removeClass('d-none');
          });

          // stop time tracker from anywhere
          $(document).on('click', '.chrono_actions_button', function (event) {
              window.livewire.emitTo('tenant.partials.timer-action-component', '$refresh');
              $('#nav-stop-timer').trigger('click');
          });

          // check tv sync current status
          $(document).on('mouseenter', '.tv-sync-progress', function (event) {
              window.livewire.emitTo('tenant.partials.retrieve-anydesk-navbar','checkTVDataSyncCurrentStatus');
          });

          // show add connection form
          $(document).on('click', '.add-connection-manually', function (event) {
              $('.add-connection-manually i').addClass('d-none');
              $('.add-connection-manually .closeManualActivityModal').removeClass('d-none');
              let customer = $('#customers').val();
              window.livewire.emit('showModal', 'tenant.activity-form-component', 'manual-activity', JSON.stringify({
                  item: null,
                  customer: customer
              }));
              $('.add-connection-manually i').removeClass('d-none');
              $('.add-connection-manually .closeManualActivityModal').addClass('d-none');
          });

          window.addEventListener('swallRecoveryData', event => {
              Swal.fire({
                  title: "{{ __('locale.Recovery data') }}",
                  text: "{{ __('locale.Do you want to keep the tracker running?') }}",
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#fff',
                  confirmButtonText: "{{ __('locale.Yes') }}",
                  cancelButtonText: "{{ __('locale.No') }}",
                  allowOutsideClick: false,
              }).then((result) => {
                  if (result.value) {
                      Livewire.emit('resumeRecentChronos');
                  } else if (result.dismiss) {
                      Livewire.emit('stopResumeTimer');
                  }
              })
          });

          window.addEventListener('swallRecoveryDataConfirm', event => {
              Swal.fire({
                  title: "{{ __('locale.Recovery data') }}",
                  text: "{{ __('locale.Do you want to save the data that was recorded?') }}",
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#fff',
                  confirmButtonText: "{{ __('locale.Yes') }}",
                  cancelButtonText: "{{ __('locale.No') }}",
              }).then((result) => {
                  if (result.value) {
                      localStorage.removeItem('storedTimerBetweenRequest');
                      Livewire.emit('process_recovery_connection');
                  } else if (result.dismiss) {
                      localStorage.removeItem('storedTimerBetweenRequest');
                      Livewire.emit('destroy_recovery_connection_session');
                  }
              })
          });

          // toastr notifications
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
              toastr.success('', event.detail.message).css("width", "fit-content");
              setTimeout(function(){
                  location.reload();
              },1500);
          });
          window.addEventListener('toastrTVRetrieveError', event => {
              toastr.error('', event.detail.message).css("width", "fit-content")
          });
          window.addEventListener('showToastrDelete', event => {
              toastr.warning('',event.detail.message).css("width","fit-content")
          });

          function initSelect2() {
              $(document).find("select.select2-dd").each(function () {
                  let self = $(this),
                      eventName = self.data('select2-dd-livewire-event'),
                      parent = self.parent(),
                      config = {
                          placeholder: "{{__('locale.Select')}}",
                          dropdownParent: parent,
                      };
                  self.wrap('<div class="position-relative"></div>').select2(config).on('change', function () {
                      if (eventName) {
                          console.log('eventName => ', eventName);
                          console.log('val => ', $(this).val());
                          livewire.emit(eventName, $(this).val());
                      }
                  });
              });
          }

          window.addEventListener('initSelect2', event => {
              let timeout = event.detail.timeout || 0;
              setTimeout(function (){
                  initSelect2();
                  // tooltip init
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
              },timeout);
          });

          Livewire.on('showModal', (component, activity_type) => {
              if (component === 'tenant.activity-form-component' && activity_type === 'edit-connection') {
                  setTimeout(function () {
                      Livewire.emitTo('tenant.activity-form-component', 'initSelect2');
                  }, 1500);
              }
          });

        window.initHourMinutePicker = (startTime, endTime) => {
            let flatpickrStartTime = $("#start_time_manual_activity"),
                flatpickrEndTime   = $("#end_time_manual_activity");
                flatpickrConfig    = {
                    enableTime:     true,
                    noCalendar:     true,
                    time_24hr:      true,
                    dateFormat:     "H:i:S",
                    disableMobile:  "true",
                    static:         true,
                    enableSeconds:  true
                },
                $locale            = "{!! config('app.locale') !!}";

            // Start Time
            if (flatpickrStartTime) {
                if ($locale === "en") {
                    flatpickr(flatpickrStartTime, $.extend(flatpickrConfig, {defaultDate: startTime}));
                } else {
                    flatpickr(flatpickrStartTime, $.extend(flatpickrConfig, {defaultDate: startTime}));
                }
            }

            // End Time
            if (flatpickrEndTime) {
                if ($locale === "en") {
                    flatpickr(flatpickrEndTime, $.extend(flatpickrConfig, {defaultDate: endTime}));
                } else {
                    flatpickr(flatpickrEndTime, $.extend(flatpickrConfig, {defaultDate: endTime}));
                }
            }
        };

        window.addEventListener('setHourMinutePicker', event => {
            initHourMinutePicker(event.detail.start_time, event.detail.end_time);
        });
      });
  </script>

</body>

</html>
