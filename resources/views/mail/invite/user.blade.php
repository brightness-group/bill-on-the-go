@component('mail::message')
# {{ __('locale.Welcome') }}!

{{ __('locale.mail invite paragraph', ['edition' => APP_EDITION == 'bdgo' ? 'Bdgo' : ucfirst(APP_EDITION)]) }}

@component('mail::button', ['url' => $url, 'color' => 'primary'])
{{ __('locale.Join us') }}
@endcomponent

<br>
{{ __('locale.Thanks') }},<br>
@if($tenantEnv)
    {{ $admin }},<br>
    {{ $tenant_name }},<br>
@else
    {{ __('locale.registered company name',['name' => $tenant_name, 'edition' => APP_EDITION == 'bdgo' ? 'Bdgo' : ucfirst(APP_EDITION)]) }},<br>
@endif
{{ $asset_url }}
@endcomponent
