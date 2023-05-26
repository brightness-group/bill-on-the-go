
    <!-- BEGIN: Vendor JS-->
    <script>
      var assetBaseUrl = "{{ asset('') }}";
    </script>
    <script src="{{asset('vendors/js/vendors.min.js')}}"></script>
    <script src="{{asset('fonts/LivIconsEvo/js/LivIconsEvo.tools.js')}}"></script>
    <script src="{{asset('fonts/LivIconsEvo/js/LivIconsEvo.defaults.js')}}"></script>
    <script src="{{asset('fonts/LivIconsEvo/js/LivIconsEvo.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    @yield('vendor-scripts')
    <script src="{{asset('vendors/js/extensions/toastr.min.js')}}" ></script>
    <script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
    <script src="{{asset('vendors/js/extensions/moment.min.js')}}"></script>
{{--    <script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>--}}
{{--    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>--}}
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->

    <script src="{{asset('js/scripts/configs/horizontal-menu.js')}}"></script>

    <script src="{{asset('js/core/app-menu.js')}}"></script>
    <script src="{{asset('js/core/app.js')}}"></script>
    <script src="{{asset('js/scripts/components.js')}}"></script>
    <script src="{{asset('js/scripts/footer.js')}}"></script>
    <script src="{{asset('js/scripts/customizer.js')}}"></script>
    <script src="{{asset('assets/js/scripts.js')}}"></script>

    <script>
        Pace.on('start', event => {
            Pace.options({
                ajax: false
            });
            // if (event instanceof Pace){}
        });
    </script>

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

        $(document).ready(function () {
            if (isMarch === true) {
                console.log('is march True');
                document.getElementById('timer').innerHTML = storedTime.toString();
                start();
            }
        });

        window.addEventListener('start', event => {
            chrono_input = document.getElementById('timer');
            start();
        });

        function start() {
            chrono_input = document.getElementById('timer');

            if (isMarch === true) {
                timeSplited = storedTime.split(':');
                hours = parseInt(timeSplited[0],10);
                minutes = parseInt(timeSplited[1],10);
                seconds = parseInt(timeSplited[2],10);
            }
            control = setInterval(cronometro, 1000);
            isMarch = true;
            console.log('start chrono_input',chrono_input);
        }

        function cronometro() {
            var hAux, mAux, sAux;
            seconds++;
            if (seconds > 59) {
                minutes ++;
                seconds=0;
            }
            if (minutes > 59) {
                hours++;
                minutes=0;
            }
            if (hours > 24) {
                hours=0;
            }
            if (seconds < 10) {
                sAux="0"+seconds;
            } else {
                sAux=seconds;
            }
            if (minutes < 10) {
                mAux="0"+minutes;
            } else {
                mAux=minutes;
            }
            if (hours < 10) {
                hAux="0"+hours;
            } else {
                hAux=hours;
            }
            chrono_input.innerHTML = hAux + " : " + mAux + " : " + sAux ;
            chrono_input_time = hAux + " : " + mAux + " : " + sAux ;
            localStorage.setItem('storedTimerBetweenRequest',chrono_input.innerHTML);
            setCookie('storedTimerBR',localStorage.getItem('storedTimerBetweenRequest'));
        }

        //define a function to set cookies
        function setCookie(name,value) {
            document.cookie = name + "=" + (value || "");
        }

        window.addEventListener('stop', event => {
            window.livewire.emitTo('tenant.partials.timer-nav-database-component', 'stopChronoValue',chrono_input.innerHTML);

            stop();
        })

        function stop() {
            console.log('stop isMarch => ', isMarch);
            if (isMarch === true) {
                console.log(' TRUE! stop isMarch => ');
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
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{asset('js/scripts/extensions/toastr.js')}}"></script>
    @yield('page-scripts')
    <!-- END: Page JS-->
