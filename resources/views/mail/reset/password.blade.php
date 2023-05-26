@component('mail::message')
# {{ __('locale.Hello') }}

{{ __('locale.You are receiving this email because we received a password reset request for your account.') }}

@component('mail::button', ['url' => $url, 'color' => 'primary'])
{{ __('locale.Reset Password') }}
@endcomponent

{{ __('locale.This password reset link will expire in') }} {{ $expire }} {{ __('locale.minutes') }}

{{ __('locale.If you’re having trouble clicking the button, copy and paste the URL below into your web browser:') }}
<a>{{ $url }}</a>

<br>
{{ __('locale.Regards') }},<br>
{{ config('app.asset_url') }}
@endcomponent

