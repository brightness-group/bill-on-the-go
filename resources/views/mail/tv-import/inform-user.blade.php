@component('mail::message')
# {{ __('locale.Hello') }} {{ !empty($user->name) ? $user->name : 'Admin'}}

{{ __("locale.mail notify tv-import to user paragraph") }}

<br>
{{ __('locale.Thanks') }},<br>
{{ $tenant_name }},<br>

{{ $asset_url }}
@endcomponent
