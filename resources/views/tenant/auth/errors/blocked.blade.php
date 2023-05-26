@extends('tenant.layouts.fullLayoutMaster')
{{-- page title --}}
@section('title', __('locale.Blocked Site'))

@section('content')
<!-- not authorized start -->
<section class="row flexbox-container">
  <div class="col-xl-7 col-md-8 col-12">
    <div class="card bg-transparent shadow-none">
      <div class="card-content">
        <div class="card-body text-center">
          <img src="{{asset('images/pages/not-authorized.png')}}" class="img-fluid" alt="not authorized" width="400">
          <h1 class="my-2 error-title">{{ __('locale.Blocked Site') }}!</h1>
          <p>
              {{ __('locale.You are unable to access this address. Please contact to administrators for more information') }}
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- not authorized end -->
@endsection
