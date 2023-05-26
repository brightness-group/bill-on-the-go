@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
$customizerHidden = 'customizer-hide'; // Hide customizer in tenant globally.
$configData = Helper::appClasses();
$pageConfigs = config('custom.custom'); // Pass default theme config to all page(s).
@endphp

@isset($configData["layout"])
@include((( $configData["layout"] === 'horizontal') ? 'tenant.theme-new.layouts.horizontalLayout' :
(( $configData["layout"] === 'blank') ? 'tenant.theme-new.layouts.blankLayout' : 'tenant.theme-new.layouts.contentNavbarLayout') ))
@endisset
