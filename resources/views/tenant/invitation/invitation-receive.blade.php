@php
    $customizerHidden = 'customizer-hide';
    $pageConfigs = ['myLayout' => 'blank'];
@endphp
@extends('tenant.theme-new.layouts.layoutMaster')

{{-- page title --}}
@section('title', __('locale.Invitation'))

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
                                    <h4 class="mb-4"><strong>{{ __('locale.Hi') }} {{ $user->name }}</strong></h4>
                                    <h4 class="mb-5">{{ __('locale.After invitation, create password for the first time') }}</h4>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form id="formAuthentication" class="mb-3" method="POST"
                                          action="{{ route('invitation-update') }}">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                                        <div class="mb-3">
                                            <label for="email" class="text-bold-600">{{ __('locale.Email') }}</label>
                                            <div>
                                                <input id="email" type="email" class="form-control" name="email"
                                                       value="{{ $user->email }}" disabled>
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
                                            <label class="form-label"
                                                   for="confirm-password">{{ __('locale.Confirm Password') }}</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="confirm-password" class="form-control"
                                                       name="password_confirmation"
                                                       aria-describedby="password"
                                                       required autocomplete="new-password"/>
                                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-group mb-50 pt-1">
                                            <div>
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('locale.Create') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- right section image --}}
                    <div class="col-md-6 d-md-block d-none text-center align-self-center p-4">
                        <div class="card-content">
                            <img class="img-fluid" src="{{asset('assets/images/pages/reset-password.png')}}"
                                 alt="branding logo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- reset password ends -->
@endsection
