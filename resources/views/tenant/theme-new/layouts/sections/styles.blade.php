<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

@if (config('app.app_edition') == 'Bdgo')
    <link rel="stylesheet" type="text/css" href="{{ mix('assets/css/Bdgo.css') }}" />
@endif

<link rel="stylesheet" href="{{ asset(mix('assets/vendor/fonts/boxicons.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('assets/vendor/fonts/fontawesome.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('assets/vendor/fonts/flag-icons.css')) }}" />
<link rel="stylesheet" href="{{ mix('assets/vendor/libs/toastr/toastr.css') }}">
<link rel="stylesheet" href="{{ mix('assets/vendor/libs/flatpickr/dist/flatpickr.css') }}">

<!-- Core CSS -->
<link rel="stylesheet" href="{{ asset(mix('assets/vendor/css' .$configData['rtlSupport'] .'/core' .($configData['style'] !== 'light' ? '-' . $configData['style'] : '') .'.css')) }}" class="{{ $configData['hasCustomizer'] ? 'template-customizer-core-css' : '' }}" />
<link rel="stylesheet" href="{{ asset(mix('assets/vendor/css' .$configData['rtlSupport'] .'/' .$configData['theme'] .($configData['style'] !== 'light' ? '-' . $configData['style'] : '') .'.css')) }}" class="{{ $configData['hasCustomizer'] ? 'template-customizer-theme-css' : '' }}" />
{{--<link rel="stylesheet" href="{{ asset(mix('assets/css/demo.css')) }}" />--}}

<link rel="stylesheet" href="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('assets/vendor/libs/typeahead-js/typeahead.css')) }}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}">


{{-- common styles --}}
<style>
    .timer-component-style.hide-shadow {
        box-shadow: none !important;
    }
    .timer-component-style {
        box-shadow: 0 1px 20px 1px #FF5B5C !important;
        background-color: #FF2829;
        color: #FFFFFF;
    }
    .timer-component-style:hover {
        box-shadow: none !important;
    }
    .add-connection-manually{
        width: 28px;
        padding: 3px !important;
    }
    .feather{
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        fill: none;
    }
    .select2-dd{
        height: 2.35rem !important;
        border: 1px solid red;
    }
    .select2-selection__placeholder,.select2-selection__rendered,.select2-results__option{
        font-size: 0.813rem;
    }

    .show-anydesk-sync-status {
        font-size: 0.9375rem;
        padding: 15px 30px;
    }

    /* sidebar logo toggle by styles */
    .app-brand-logo img.brand-square-logo , .app-brand-logo svg.brand-square-logo {
        display: none;
    }
    .icon-status{
        display: none;
    }
    .layout-menu-collapsed .app-brand-logo img.brand-square-logo{
        display: block;
    }
    .layout-menu-collapsed .app-brand-logo img.brand-rectangle-logo, .layout-menu-collapsed .text-status{
        display: none;
    }
    .layout-menu-collapsed.layout-menu-hover .app-brand-logo img.brand-rectangle-logo, .layout-menu-collapsed .icon-status{
        display: block;
    }
    .layout-menu-collapsed.layout-menu-hover .app-brand-logo img.brand-square-logo{
        display: none;
    }
    .tv-sync-status-btn.sync-running svg{
        animation: anim 2.3s linear infinite;
        color: #fdac41;
    }
    @keyframes anim {
        from {
            transform: rotate(360deg);
        }
        to {
            transform: rotate(0deg);
        }
    }
</style>
<!-- Vendor Styles -->
@yield('vendor-style')


<!-- Page Styles -->
@yield('page-style')

<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="{{ mix('assets/css/style.css') }}">
