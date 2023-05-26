@php
    $customizerHidden = 'customizer-hide';
    $pageConfigs = ['myLayout' => 'blank'];
@endphp
@extends('theme-new.layouts.layoutMaster')

{{-- page title --}}
@section('title', __('locale.Forgot Password'))

@section('vendor-style')
    <!-- Vendor -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}"/>
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
    <script src="{{asset('assets/js/pages-auth.js')}}"></script>
@endsection


@section('content')

    {{-- forgot password start --}}
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <div class="row m-0 bg-authentication">
                    {{-- left section-forgot password --}}
                    <div class="col-md-6 col-12 px-0">
                        <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                            <!-- Language -->
                            <div class="d-flex justify-content-end {{ session('status') ? 'mt-2' : 'mt-n4' }}">
                                @if(app()->getLocale() == 'de')
                                    <a class="px-3 py-1" href="{{url('lang/en')}}">
                                        <span class="align-middle">{{__('locale.English')}}</span>
                                    </a>
                                @elseif(app()->getLocale() == 'en')
                                    <a class="px-3 py-1" href="{{ url('lang/de')}}">
                                        <span class="align-middle">{{__('locale.German')}}</span>
                                    </a>
                                @endif
                            </div>
                            <!--/ Language -->
                            <div class="card-header pb-1">
                                <div class="card-title">
                                    <h4 class="text-center mb-2">{{ __('locale.Forgot Your Password?') }}</h4>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-center mb-2">
                                <a href="{{route('login')}}"
                                   class="card-link text-nowrap"><u>{{ __('locale.Back to login') }}</u></a>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <div class="text-muted text-center mb-2">
                                        <small>{{ __('locale.Enter the email you used when you joined and we will send you temporary password') }}</small>
                                    </div>
                                    <form id="formAuthentication" method="POST" class="mb-3"
                                          action="{{ route('password.email') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="email"
                                                   class="form-label">{{ __('locale.E-Mail Address') }}</label>
                                            <input type="email"
                                                   class="form-control @error('email') is-invalid @enderror" id="email"
                                                   name="email" value="{{ old('email') }}" required autocomplete="email"
                                                   placeholder="{{ __('locale.Email') }}" autofocus>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <button class="btn btn-primary d-grid w-100">
                                            {{ __('locale.Request password') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- right section image --}}
                    <div class="col-md-6 d-md-block d-none text-center align-self-center p-4">
                        <div class="card-content">
                            <img class="img-fluid" src="{{asset('assets/images/pages/forgot-password.png')}}"
                                 alt="branding logo" width="300">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- forgot password ends --}}
@endsection




{{--<x-guest-layout>--}}
{{--    <x-jet-authentication-card>--}}
{{--        <x-slot name="logo">--}}
{{--            <x-jet-authentication-card-logo />--}}
{{--        </x-slot>--}}

{{--        <div class="mb-4 text-sm text-gray-600">--}}
{{--            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}--}}
{{--        </div>--}}

{{--        @if (session('status'))--}}
{{--            <div class="mb-4 font-medium text-sm text-green-600">--}}
{{--                {{ session('status') }}--}}
{{--            </div>--}}
{{--        @endif--}}

{{--        <x-jet-validation-errors class="mb-4" />--}}

{{--        <form method="POST" action="{{ route('password.email') }}">--}}
{{--            @csrf--}}

{{--            <div class="block">--}}
{{--                <x-jet-label for="email" value="{{ __('Email') }}" />--}}
{{--                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />--}}
{{--            </div>--}}

{{--            <div class="flex items-center justify-end mt-4">--}}
{{--                <x-jet-button>--}}
{{--                    {{ __('Email Password Reset Link') }}--}}
{{--                </x-jet-button>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </x-jet-authentication-card>--}}
{{--</x-guest-layout>--}}
