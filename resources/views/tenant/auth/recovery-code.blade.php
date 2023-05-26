@php
    $customizerHidden = 'customizer-hide';
    $pageConfigs = ['myLayout' => 'blank'];
@endphp

@extends('tenant.theme-new.layouts.layoutMaster')
{{-- title --}}
@section('title', __('locale.Two Factor Recovery Code Page'))

@section('vendor-style')
    <!-- Vendor -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

{{-- page scripts --}}
@section('page-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
    <style>
        .authentication-wrapper.authentication-basic .authentication-inner {
            max-width: 25rem;
            position: relative;
        }
    </style>
@endsection

@section('content')
    {{-- Recovery code START --}}
    <div class="authentication-wrapper authentication-basic px-4">
        <div class="authentication-inner py-4">
            <!--  Two Steps Recovery Code  -->
            <div class="card">
                <!-- Language -->
                <div class="d-flex justify-content-end {{ session('status') ? 'mt-3' : 'mt-1' }}">
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
                        <h4 class="text-center mb-2">
                            {{ __('locale.Two factor Recovery Code') }}
                        </h4>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <p class="mb-0 fw-semibold">
                            {{ __('locale.Please enter your recovery code to login.') }}
                        </p>
                        <form action="{{route('two-factor.login')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <div class="auth-input-wrapper d-flex justify-content-sm-between">
                                    <input id="recovery_code" type="recovery_code"
                                           class="form-control my-2 @error('recovery_code') is-invalid @enderror"
                                           name="recovery_code" required autocomplete="current-recovery_code">
                                </div>
                                @error('recovery_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100 mb-3">
                                {{ __('locale.Submit') }}
                            </button>
                            <div class="text-center">
                                <a href="{{ route('two-factor.login') }}">
                                    {{ __('locale.or entering on of two factor authentication code') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- / Two Steps Recovery Code  -->
        </div>
    </div>
    {{-- Recovery code END --}}
@endsection
