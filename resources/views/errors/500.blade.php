@extends('layouts.fullLayoutMaster')
{{-- page title --}}
@section('title', __('locale.Service Unavailable'))

@section('content')
<!-- not authorized start -->
<section class="row flexbox-container">
  <div class="col-xl-7 col-md-8 col-12">
    <div class="card bg-transparent shadow-none">
      <div class="card-content">
        <div class="card-body text-center">
          <img src="{{asset('images/logo/billonthego logo.svg')}}" class="img-fluid" alt="service unavailable" width="400">
          <h1 class="my-2 error-title"><strong>{{ __('ups ...') }}</strong></h1>
          <p style="font-size: 22px;font-weight: bold;">
              {{ __('locale.service unavailable') }}
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- not authorized end -->
@endsection
