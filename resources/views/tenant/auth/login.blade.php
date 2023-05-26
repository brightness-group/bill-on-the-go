@php
    $customizerHidden = 'customizer-hide';
    $pageConfigs = ['myLayout' => 'blank'];
@endphp

@extends('tenant.theme-new.layouts.layoutMaster')

@section('title', __('locale.Login'))

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

    {{-- refresh-info  --}}
    @livewire('tenant.refresh-info')

    {{-- login page start  --}}
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <div class="row m-0 bg-authentication">
                    {{-- left section-login --}}
                    <div class="col-md-6 col-12 px-0">
                        <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                            <!-- Language -->
                            <div class="d-flex justify-content-end {{ session('status') ? 'mt-2' : 'mt-n2' }}">
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
                                @if (session('already'))
                                    <div class="alert alert-success">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        <i class="bx bx-task mr-1"></i>
                                        {{ session('already') }}
                                    </div>
                                @elseif (session('status'))
                                    <div class="alert alert-success">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        <i class="bx bx-check mr-1"></i>
                                        {{ session('status') }}
                                    </div>
                                @elseif(session('unable'))
                                    <div class="alert alert-danger">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        <i class="bx bx-task mr-1"></i>
                                        {{ session('unable') }}
                                    </div>
                                @elseif (session('success'))
                                    <div class="alert alert-success">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        <i class="bx bx-check mr-1"></i>
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <div class="card-title">
                                    <h4 class="text-center mb-2">{{ __('locale.Welcome Back to') }} {{ $tenant->name }}</h4>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    {{-- form  --}}
                                    <form id="formAuthentication" class="mb-3" method="POST"
                                          action="{{ route('login') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label"
                                                   for="email">{{ __('locale.E-Mail Address') }}</label>
                                            <input id="email" type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   name="email" value="{{ old('email') }}" autocomplete="email"
                                                   autofocus placeholder="{{ __('locale.Email') }}">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3 form-password-toggle">
                                            <div class="d-flex justify-content-between">
                                                <label class="form-label"
                                                       for="password">{{ __('locale.Password') }}</label>
                                                <a href="{{ route('password.request') }}">
                                                    <small>{{__('locale.Forgot Password')}}?</small>
                                                </a>
                                            </div>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="password"
                                                       class="form-control @error('password') is-invalid @enderror"
                                                       name="password" autocomplete="current-password"
                                                       placeholder="{{ __('locale.Password') }}"
                                                       aria-describedby="password"/>
                                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                       id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember-me">
                                                    <small>{{ __('locale.Keep me logged in') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit"
                                                class="btn btn-primary glow w-100 position-relative">{{ __('locale.Login') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- right section image --}}
                    <div class="col-md-6 d-md-block d-none text-center align-self-center p-4">
                        <div class="card-content">
                            @php
                                $path_auth_logo = \App\Helpers\CoreHelpers::getFileUrl($tenant->logo,'auth');
                            @endphp
                            <img class="img-fluid"
                                 src="{{!empty($path_auth_logo) ? url($path_auth_logo) : asset('assets/images/pages/login.png')}}"
                                 alt="branding logo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script type="text/javascript">
        if (localStorage.getItem('storedTimerBetweenRequest'))
            localStorage.removeItem('storedTimerBetweenRequest');
    </script>

@endsection
