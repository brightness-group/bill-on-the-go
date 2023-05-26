<!-- BEGIN: Vendor JS-->
<script src="{{ asset(mix('assets/vendor/libs/jquery/jquery.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/popper/popper.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/js/bootstrap.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/hammer/hammer.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/i18n/i18n.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/typeahead-js/typeahead.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/toastr/toastr.js')) }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/screenfull.js/5.1.0/screenfull.min.js"></script>
<script src="{{ asset(mix('assets/vendor/js/menu.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/block-ui/block-ui.js')) }}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{ mix('assets/js/tenant/scripts.js') }}"></script>

@if (config('app.app_edition') == 'Bdgo')
    <script type="text/javascript">
        const LANG = {
            "are_you_sure" : "{{ __('locale.Are You Sure ?') }}",
            "delete" : "{{ __('locale.Delete') }}",
            "no_results_found" : "{{ __('locale.No results found') }}",
            "searching" : "{{ __('locale.Searching') }}"
        };
    </script>

    <script type="text/javascript" src="{{ mix('assets/js/tenant/Bdgo.js') }}"></script>
    <script type="text/javascript" src="{{ mix('assets/js/form-confirmation.js') }}"></script>
@endif

<script>
    var dark_mode = "{{__('locale.Dark Mode')}}";
    var light_mode = "{{__('locale.Light Mode')}}";
    var app_locale = "{!! config('app.locale') !!}";
</script>
@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- END: Theme JS-->
<!-- START: Time tracker JS-->
<script>

    var control = null;
    var chrono_input_time = "00 : 00 : 00";
    var chrono_input = null;
    var isMarch = false;
    var storedTime = localStorage.getItem('storedTimerBetweenRequest');
    if (storedTime !== null) {
        isMarch = true;
    }
    var timeInicial = null;
    var acumularTime = 0;
    var hours = 0;
    var minutes = 0;
    var seconds = 0;

    window.addEventListener('updateStoredTime', event => {
        chrono_input = document.getElementById('timer');
        localStorage.setItem('storedTimerBetweenRequest', event.detail.new_time);
        storedTime = event.detail.new_time;
        setCookie('storedTimerBR', event.detail.new_time);

        setTimeout(initTimer, 500);
    });

    window.addEventListener('start', event => {
        chrono_input = document.getElementById('timer');

        start();
    });

    function initTimer() {
        if (isMarch === true) {
            document.getElementById('timer').innerHTML = storedTime.toString();

            start();
        }
    }

    function start() {
        chrono_input = document.getElementById('timer');

        if (isMarch === true) {
            timeSplited = storedTime.split(':');
            hours = parseInt(timeSplited[0], 10);
            minutes = parseInt(timeSplited[1], 10);
            seconds = parseInt(timeSplited[2], 10);
        }

        clearTimeout(control);

        control = setInterval(cronometro, 1000);

        isMarch = true;
    }

    function cronometro() {
        var hAux, mAux, sAux;
        seconds++;
        if (seconds > 59) {
            minutes++;
            seconds = 0;
        }
        if (minutes > 59) {
            hours++;
            minutes = 0;
        }
        // if (hours > 24) {
        //     hours = 0;
        // }
        if (seconds < 10) {
            sAux = "0" + seconds;
        } else {
            sAux = seconds;
        }
        if (minutes < 10) {
            mAux = "0" + minutes;
        } else {
            mAux = minutes;
        }
        if (hours < 10) {
            hAux = "0" + hours;
        } else {
            hAux = hours;
        }
        chrono_input.innerHTML = hAux + " : " + mAux + " : " + sAux;
        chrono_input_time = hAux + " : " + mAux + " : " + sAux;
        localStorage.setItem('storedTimerBetweenRequest', chrono_input.innerHTML);
        setCookie('storedTimerBR', localStorage.getItem('storedTimerBetweenRequest'));
    }

    //define a function to set cookies
    function setCookie(name, value) {
        document.cookie = name + "=" + (value || "");
    }

    window.addEventListener('stop', event => {
        stopChrono();
    });

    function stopChrono()
    {
        window.livewire.emitTo('tenant.partials.timer-nav-database-component', 'stopChronoValue');

        stop();
    }

    function stop() {
        if (isMarch === true) {
            isMarch = false;
            clearInterval(control);
            storedTime = null;
            timeSplited = null;
            hours = 0;
            minutes = 0;
            seconds = 0;
            localStorage.removeItem('storedTimerBetweenRequest');
        }
    }

</script>
<!-- END: Time tracker JS-->

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
