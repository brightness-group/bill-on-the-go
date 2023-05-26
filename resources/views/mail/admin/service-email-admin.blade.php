@component('mail::message')
# {{ __('locale.Hello') }} {{ !empty($user->name) ? $user->name : 'Admin'}}

{!! __("locale.Service Mail to Admin Content") !!}

<br>
{{ __('locale.Kind Regards') }},<br>
Teambilling Support,<br>
@endcomponent
