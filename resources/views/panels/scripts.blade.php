
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
    <script src="{{asset('vendors/js/ui/jquery.sticky.js')}}"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
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
            // if (event instanceof Pace)
            // {}
        })
    </script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{asset('js/scripts/extensions/toastr.js')}}"></script>
    <script src="{{asset('js/scripts/forms/select/form-select2.js')}}"></script>
    @yield('page-scripts')
    <!-- END: Page JS-->
