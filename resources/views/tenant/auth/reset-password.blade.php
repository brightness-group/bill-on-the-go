@php
    $customizerHidden = 'customizer-hide';
    $pageConfigs = ['myLayout' => 'blank'];
@endphp
@extends('tenant.theme-new.layouts.layoutMaster')
{{-- page title --}}
@section('title', __('locale.Reset Password'))

@section('vendor-style')
    <!-- Vendor -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
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
    <!-- reset password start -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <div class="row m-0 bg-authentication">
                    {{-- reset password section --}}
                    <div class="col-md-6 col-12 px-0">
                        <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                            <!-- Language -->
                            <div class="d-flex justify-content-end {{ session('status') ? 'mt-4' : 'mt-2' }}">
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
                                    <h4 class="mb-2">{{ __('locale.Reset your Password') }}</h4>
                                    <p class="mb-5">{{ __('locale.for') }} <span class="fw-bold">{{ request()->get('email') }}</span></p>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('password.update') }}">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ request()->token }}">
                                        <div class="form-group mb-50">
                                            <label for="email" class="form-label" hidden>{{ __('locale.E-Mail Address') }}</label>
                                            <div>
                                                <input id="email" type="email" hidden class="form-control @error('email') is-invalid @enderror" name="email" value="{{ request()->get('email') ?? old('email') }}" required autocomplete="email" autofocus>
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3 form-password-toggle">
                                            <label class="form-label" for="password">{{ __('locale.Password') }}</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="password"
                                                       class="form-control  @error('password') is-invalid @enderror"
                                                       name="password"
                                                       placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                       aria-describedby="password"
                                                       required autocomplete="new-password"/>
                                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3 form-password-toggle">
                                            <label class="form-label" for="confirm-password">{{ __('locale.Confirm Password') }}</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="confirm-password" class="form-control" name="password_confirmation"
                                                       placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password"
                                                       required autocomplete="new-password"/>
                                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary d-grid w-100 mb-3">
                                            {{ __('locale.Reset Password') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- right section image --}}
                    <div class="col-md-6 d-md-block d-none text-center align-self-center p-4">
                        <div class="card-content">
                            <img class="img-fluid" src="{{asset('assets/images/pages/reset-password.png')}}" alt="branding logo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- reset password ends -->
@endsection








{{--<x-guest-layout>--}}
{{--    <x-jet-authentication-card>--}}
{{--        <x-slot name="logo">--}}
{{--            <x-jet-authentication-card-logo />--}}
{{--        </x-slot>--}}

{{--        <x-jet-validation-errors class="mb-4" />--}}

{{--        <form method="POST" action="{{ route('password.update') }}">--}}
{{--            @csrf--}}

{{--            <input type="hidden" name="token" value="{{ $request->route('token') }}">--}}

{{--            <div class="block">--}}
{{--                <x-jet-label for="email" value="{{ __('Email') }}" />--}}
{{--                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />--}}
{{--            </div>--}}

{{--            <div class="mt-4">--}}
{{--                <x-jet-label for="password" value="{{ __('Password') }}" />--}}
{{--                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />--}}
{{--            </div>--}}

{{--            <div class="mt-4">--}}
{{--                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />--}}
{{--                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />--}}
{{--            </div>--}}

{{--            <div class="flex items-center justify-end mt-4">--}}
{{--                <x-jet-button>--}}
{{--                    {{ __('Reset Password') }}--}}
{{--                </x-jet-button>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </x-jet-authentication-card>--}}
{{--</x-guest-layout>--}}
