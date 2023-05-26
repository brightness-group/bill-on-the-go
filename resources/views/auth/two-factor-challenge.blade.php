@php
    $customizerHidden = 'customizer-hide';
    $pageConfigs = ['myLayout' => 'blank'];
@endphp
@extends('theme-new.layouts.layoutMaster')

@section('title',__('locale.Two factor Challenge'))

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

@section('vendor-script')
    <script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
    <script>
        let $local = "{{app()->getLocale()}}";
    </script>
    <script src="{{asset('assets/js/pages-auth.js')}}"></script>
    <script src="{{asset('assets/js/pages-auth-two-steps.js')}}"></script>
@endsection

@section('content')
    <div class="authentication-wrapper authentication-basic px-4">
        <div class="authentication-inner py-4">
            <!--  Two Steps Verification -->
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
                        <h4 class="text-center mb-2">{{ __('locale.Two factor Challenge') }}</h4>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <p class="mb-0 fw-semibold">{{ __('locale.Please enter your authentication code to login.') }}</p>
                        <form id="twoStepsForm" action="{{ route('two-factor.login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <div class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">
                                    <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1" autofocus>
                                    <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                                    <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                                    <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                                    <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                                    <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                                </div>
                                <!-- Create a hidden field which is combined by 3 fields above -->
                                <input type="hidden" name="code" />
                                @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                @error('recovery_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <button class="btn btn-primary d-grid w-100 mb-3">
                                {{ __('locale.Submit') }}
                            </button>
                            <div class="text-center">
                                <a href="{{ route('two-factor-recovery-code.login') }}">{{ __('locale.or entering on of your recovery codes') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- / Two Steps Verification -->
        </div>
    </div>
@endsection
