@extends('layouts.fullLayoutMaster')
{{-- page title --}}
@section('title', __('locale.Register'))
{{-- page scripts --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/authentication.css')}}">
@endsection


@section('content')
    <!-- register section starts -->
    <section class="row flexbox-container">
        <div class="col-xl-8 col-10">
            <div class="card bg-authentication mb-0">
                <div class="row m-0">
                    <!-- register section left -->
                    <div class="col-md-6 col-12 px-0">
                        <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                            <div class="card-header pb-1">
                                <div class="card-title">
                                    <h4 class="text-center mb-2">{{ __('locale.Sign up') }}</h4>
                                </div>
                            </div>
                            <div class="text-center">
                                <p> <small>{{ __('locale.Please enter your details to sign up and be part of our great community') }}</small>
                                </p>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <div class="form-group mb-50">
                                            <label for="name" class="text-bold-600">{{ __('locale.Name') }}</label>
                                            <div>
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group mb-50">
                                            <label for="email" class="text-bold-600">{{ __('locale.E-Mail Address') }}</label>
                                            <div>
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group mb-50">
                                            <label for="password" class="text-bold-600">{{ __('locale.Password') }}</label>
                                            <div>
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group mb-50">
                                            <label for="password-confirm" class="text-bold-600">{{ __('locale.Confirm Password') }}</label>
                                            <div>
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-6 offset-md-4">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('locale.Register') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <hr>
                                    <div class="text-center"><small class="mr-25">{{ __('locale.Already have an account?') }}</small>
                                        <a href="{{route('login')}}"><small>{{ __('locale.Sign in') }}</small> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- image section right -->
                    <div class="col-md-6 d-md-block d-none text-center align-self-center p-3">
                        <img class="img-fluid" src="{{asset('images/pages/register.png')}}" alt="branding logo">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- register section endss -->
@endsection
