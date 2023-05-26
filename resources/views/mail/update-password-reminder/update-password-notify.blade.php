@component('mail::message')
# {{ __('locale.Hello') }} {{ !empty($user->name) ? $user->name : 'Admin'}}

{{ __("locale.Your password has not been changed for more than 6 months.") }}
<br>
{{ __("locale.Please create a new one in your account.") }}

@component('mail::button', ['url' => route('login'), 'color' => 'primary'])
{{ __('locale.Login now') }}
@endcomponent

<br>
{{ __('locale.Regards') }},<br>
{{ __('locale.Support') }} <a href="{{ config('app.asset_url')}}"> {{config('app.name')}}</a>
@endcomponent


{{--
Hallo [Accountname],
dein Passwort wurde länger als 6 Monate nicht geändert.
Bitte hinterlege ein neues in deinem Account.
Button [jetzt anmelden]
Support Teambilling

Hello [account name],

your password has not been changed for more than 6 months.

Please create a new one in your account.

Button [login now]

Support Teambilling

--}}
