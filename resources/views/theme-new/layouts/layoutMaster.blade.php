@isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
    $customizerHidden = 'customizer-hide'; // Hide customizer in tenant globally.
    $configData = Helper::appClasses();
    $pageConfigs = config('custom.custom'); // Pass default theme config to all back office page(s).
@endphp

@isset($configData["layout"])
    @include((( $configData["layout"] === 'horizontal') ? 'theme-new.layouts.horizontalLayout' :
    (( $configData["layout"] === 'blank') ? 'theme-new.layouts.blankLayout' : 'theme-new.layouts.contentNavbarLayout') ))
@endisset
