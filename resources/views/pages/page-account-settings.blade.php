@extends('theme-new.layouts.layoutMaster')
{{-- page title --}}
@section('title', __('locale.Account Settings'))
{{-- vendor styles --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
{{-- page styles --}}
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/validation/form-validation.css')}}">
    <style>
        #page-account-settings .nav-pills .nav-link.active, #page-account-settings .nav-pills .nav-link.active:hover, #page-account-settings .nav-pills .nav-link.active:focus {
            background-color: #6d7782;
        }
    </style>
@endsection

@section('content')
    <!-- account setting page start -->
    <section id="page-account-settings">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="nav-align-left">
                        <ul class="nav nav-pills border-end p-4" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active w-px-250" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#account-vertical-general" aria-controls="account-vertical-general"
                                        aria-selected="true">
                                    <i class="bx bx-cog"></i>
                                    <span>{{ __('locale.General') }}</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link w-px-250" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#account-vertical-password"
                                        aria-controls="account-vertical-password" aria-selected="false">
                                    <i class="bx bx-lock"></i>
                                    <span>{{ __('locale.Reset Password') }}</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link w-px-250" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#account-vertical-info"
                                        aria-controls="account-vertical-info" aria-selected="false">
                                    <i class="bx bx-info-circle"></i>
                                    <span>{{ __('locale.Two Factor Authentication (2FA)') }}</span>
                                </button>
                            </li>
                        </ul>
                        {{-- General --}}
                        <div class="tab-content shadow-none">
                            <div class="tab-pane fade show active" id="account-vertical-general" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        @livewire('user-name-email-profile-component')
                                    </div>
                                </div>
                            </div>
                            {{--Change password--}}
                            <div class="tab-pane fade" id="account-vertical-password" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        @livewire('user-reset-password-profile-component')
                                    </div>
                                </div>
                            </div>
                            {{-- Two factor authentication --}}
                            <div class="tab-pane fade" id="account-vertical-info" role="tabpanel">
                                <div class="row gl-mt-3">
                                    <div class="container text-center">
                                        @livewire('two-factor-auth-component')
                                    </div>
                                    <div class="modal fade" id="confirmPasswordForTwoFactorModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            @livewire('confirm-password-for-two-factor-component')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- account setting page ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{asset('vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('vendors/js/extensions/dropzone.min.js')}}"></script>
@endsection

@section('page-script')
    <script src="{{asset('js/scripts/pages/page-account-settings.js')}}"></script>

    <script>

        $(window).blur(function (){
            Livewire.emit('resetPasswordComponent');
        });

        var e = document.getElementById('account-vertical-password');
        var i = document.getElementById('current_password');

        var observer = new MutationObserver(function (event) {
            if(!$('#account-vertical-password').hasClass('active')) {
                Livewire.emit('resetPasswordComponent');
            }
        })

        observer.observe(e, {
            attributes: true,
            attributeFilter: ['class'],
            childList: false,
            characterData: false
        })

        toastr.options = {
            positionClass: 'toast-top-center',
            showDuration: 1000,
            timeOut: 3000,
            hideDuration: 2000,
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut',
            preventDuplicates: false,
        }
        window.addEventListener('showToastrSuccess', event => {
            toastr.success('',event.detail.message)
        })
        window.addEventListener('showToastrDelete', event => {
            toastr.warning('',event.detail.message)
        })

        window.addEventListener('open-confirming-password', event => {
            $("#confirmPasswordForTwoFactorModal").modal('show');
        })

        window.addEventListener('close-confirming-password', event => {
            $("#confirmPasswordForTwoFactorModal").modal('hide');
        })
    </script>
@endsection
