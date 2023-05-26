@extends('tenant.layouts.fullLayoutMaster')
{{-- page title --}}
@section('title', __('locale.Verify Email Address'))
{{-- page scripts --}}
@section('page-styles')
  <link rel="stylesheet" type="text/css" href="{{asset('css/pages/authentication.css')}}">
@endsection

@section('content')
  <!-- reset password start -->
  <section class="row flexbox-container">
    <div class="col-xl-7 col-10">
      <div class="card bg-authentication mb-0">
        <div class="row m-0">
          <!-- left section-login -->
          <div class="col-md-6 col-12 px-0">
            <div class="card disable-rounded-right d-flex justify-content-center mb-0 p-2 h-100">
              <div class="card-header">{{ __('locale.Verify Your Email Address') }}</div>
              <div class="card-content">
                <div class="card-body">
                  @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                      {{ __('locale.A fresh verification link has been sent to your email address.') }}
                    </div>
                  @endif
                  {{ __('locale.Before proceeding, please check your email for a verification link.') }}
                  {{ __('locale.If you did not receive the email') }},
                  <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-dark">{{ __('locale.click here to request another') }}</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- right section image -->
          <div class="col-md-6 d-md-block d-none text-center align-self-center p-3">
            <img class="img-fluid" src="{{asset('assets/images/pages/lock-screen.png')}}" alt="branding logo">
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- reset password ends -->
@endsection





