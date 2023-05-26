@extends('tenant.theme-new.layouts.layoutMaster')
{{-- page title --}}
@section('title', __('locale.Account Settings'))
{{-- vendor styles --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
@endsection
{{-- page styles --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/validation/form-validation.css')}}">

@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{asset('frontend/css/users-component_css.css')}}">
    <link rel="stylesheet" href="{{asset('/frontend/css/tariff_component_css.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/loading_states_awesome.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css"/>
    <link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.css"/>
    <style>
        .picker {
            font-size: 10px;
            width: 200px;
        }
        .image_area {
            position: relative;
        }
        img#uploaded_sys_profile_photo,#sample_image,#image {
            display: block;
            max-width: 100%;
        }
        /*.preview {*/
        /*    overflow: hidden;*/
        /*    width: 160px;*/
        /*    height: 160px;*/
        /*    margin: 10px;*/
        /*    border: 1px solid red;*/
        /*}*/
        .modal-lg{
            max-width: 1000px !important;
        }
        .overlay {
            position: absolute;
            bottom: 22px;
            left: 0;
            right: 0;
            background-color: rgba(255, 255, 255, 0.5);
            overflow: hidden;
            height: 0;
            transition: .5s ease;
            width: 100%;
        }
        .image_area:hover .overlay {
            height: 50%;
            cursor: pointer;
        }
        .text {
            color: #333;
            font-size: 12px;
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <!-- account setting page start -->
    <section id="page-account-settings">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <!-- left menu section -->
                    <div class="col-md-3 mt-2 mb-2 mb-md-0 pills-stacked">
                        <ul class="nav nav-pills flex-column ">
                        @if(auth()->user()->locale == 'en')
                            @if(auth()->user()->hasRole('Admin'))
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center {{ session()->has('redirectUsersTab') ? '' : 'active show' }}"
                                       id="account-pill-connect" data-toggle="pill" href="#account-vertical-connect" aria-expanded="false">
                                        <i class="bx bx-sitemap"></i>
                                        <span>{{ __('locale.Connect App') }}</span>
                                    </a>
                                </li>
                            @endif
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center {{ auth()->user()->hasRole('User') ? 'active show' : '' }}"
                                       id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true">
                                        <i class="bx bx-cog"></i>
                                        <span>{{ __('locale.General') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="account-pill-password" data-toggle="pill"
                                       href="#account-vertical-password" aria-expanded="false">
                                        <i class="bx bx-lock"></i>
                                        <span>{{ __('locale.Reset Password') }}</span>
                                    </a>
                                </li>
                            @if(auth()->user()->hasRole('Admin'))
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="account-pill-tariff" data-toggle="pill"
                                       href="#account-vertical-tariff" aria-expanded="false">
                                        <i class="bx bx-wallet"></i>
                                        <span>{{ __('locale.Tariffs') }}</span>
                                    </a>
                                </li>
                            @endif
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="account-pill-info" data-toggle="pill"
                                       href="#account-vertical-info" aria-expanded="false">
                                        <i class="bx bx-info-circle"></i>
                                        <span>{{ __('locale.Two Factor Authentication (2FA)') }}</span>
                                    </a>
                                </li>
                            @if(auth()->user()->hasRole('Admin'))
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center
                                        {{ session()->has('redirectUsersTab') ? 'active show' : '' }}"
                                       id="account-pill-users" data-toggle="pill" href="#account-vertical-users" aria-expanded="false">
                                        <i class="bx bx-group"></i>
                                        <span>{{ __('locale.User Management') }}</span>
                                    </a>
                                </li>
                            @endif
                        @else
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center
                                        {{ session()->has('anydesk_callback')               ||     session()->has('anydesk_revoked')        ||
                                           session()->has('anydesk_revoked_fails')          ||     session()->has('anydesk_callback_fails') ||
                                           session()->has('anydesk_refreshToken_refreshed') ||     session()->has('anydesk_refreshToken_fails') ||
                                           session()->has('redirectUsersTab')
                                            ? '' : 'active show' }}"
                                       id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true">
                                        <i class="bx bx-cog"></i>
                                        <span>{{ __('locale.General') }}</span>
                                    </a>
                                </li>
                            @if(auth()->user()->hasRole('Admin'))
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center
                                        {{ session()->has('anydesk_callback')               ||     session()->has('anydesk_revoked')        ||
                                           session()->has('anydesk_revoked_fails')          ||     session()->has('anydesk_callback_fails') ||
                                           session()->has('anydesk_refreshToken_refreshed') ||     session()->has('anydesk_refreshToken_fails')
                                             ? 'active show' : '' }}"
                                       id="account-pill-connect" data-toggle="pill" href="#account-vertical-connect" aria-expanded="false">
                                        <i class="bx bx-sitemap"></i>
                                        <span>{{ __('locale.Connect App') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center
                                        {{ session()->has('redirectUsersTab') ? 'active show' : '' }}"
                                       id="account-pill-users" data-toggle="pill"
                                       href="#account-vertical-users" aria-expanded="false">
                                        <i class="bx bx-group"></i>
                                        <span>{{ __('locale.User Management') }}</span>
                                    </a>
                                </li>
                            @endif
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="account-pill-password" data-toggle="pill"
                                       href="#account-vertical-password" aria-expanded="false">
                                        <i class="bx bx-lock"></i>
                                        <span>{{ __('locale.Reset Password') }}</span>
                                    </a>
                                </li>
                            @if(auth()->user()->hasRole('Admin'))
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="account-pill-tariff" data-toggle="pill"
                                       href="#account-vertical-tariff" aria-expanded="false">
                                        <i class="bx bx-wallet"></i>
                                        <span>{{ __('locale.Tariffs') }}</span>
                                    </a>
                                </li>
                            @endif
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="account-pill-info" data-toggle="pill"
                                       href="#account-vertical-info" aria-expanded="false">
                                        <i class="bx bx-info-circle"></i>
                                        <span>{{ __('locale.Two Factor Authentication (2FA)') }}</span>
                                    </a>
                                </li>
                        @endif
                        </ul>
                    </div>
                    <!-- right content section -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="tab-content">

                                        @if(auth()->user()->locale == 'en')
                                            {{--  Connect App  --}}
                                            <div class="tab-pane {{ session()->has('redirectUsersTab') ? '' : 'active show' }}"
                                                 id="account-vertical-connect" role="tabpanel" aria-labelledby="account-pill-connect" aria-expanded="false">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @if(auth()->user()->hasRole('Admin'))
                                                            @livewire('tenant.test-anydesk-component')
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- General --}}
                                            <div class="tab-pane fade" id="account-vertical-general" role="tabpanel" aria-labelledby="account-pill-general" aria-expanded="true">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @livewire('user-name-email-profile-component')
                                                    </div>
                                                </div>
                                            </div>

                                            {{--Reset password--}}
                                            <div class="tab-pane fade " id="account-vertical-password" role="tabpanel"
                                                 aria-labelledby="account-pill-password" aria-expanded="false">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @livewire('user-reset-password-profile-component')
                                                    </div>
                                                </div>
                                            </div>

                                            {{--  Tariff  --}}
                                            <div class="tab-pane fade " id="account-vertical-tariff" role="tabpanel"
                                                 aria-labelledby="account-pill-tariff" aria-expanded="false">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @if(auth()->user()->hasRole('Admin'))
                                                            @livewire('tenant.tariff-component')
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Two factor authentication --}}
                                            <div class="tab-pane fade" id="account-vertical-info" role="tabpanel"
                                                 aria-labelledby="account-pill-info" aria-expanded="false">

                                                <div class="row gl-mt-3">
                                                    <div class="container text-center">
                                                        @livewire('two-factor-auth-component')
                                                    </div>
                                                    <div class="modal fade" id="confirmPasswordForTwoFactorModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="confirmModalLabel">{{ __('locale.Two Factor Authentication') }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @livewire('confirm-password-for-two-factor-component')
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--  Users component  --}}
                                            <div class="tab-pane {{ session()->has('redirectUsersTab') ? 'active show' : '' }}"
                                                 id="account-vertical-users" role="tabpanel" aria-labelledby="account-pill-users" aria-expanded="false">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @if(auth()->user()->hasRole('Admin'))
                                                            @livewire('tenant.users-component')
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{-- General --}}
                                            <div class="tab-pane
                                                 {{ session()->has('anydesk_callback')               ||     session()->has('anydesk_revoked')        ||
                                                    session()->has('anydesk_revoked_fails')          ||     session()->has('anydesk_callback_fails') ||
                                                    session()->has('anydesk_refreshToken_refreshed') ||     session()->has('anydesk_refreshToken_fails') ||
                                                    session()->has('redirectUsersTab') ? '' : 'active show' }}"
                                                 id="account-vertical-general" role="tabpanel" aria-labelledby="account-pill-general" aria-expanded="true">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @livewire('user-name-email-profile-component')
                                                    </div>
                                                </div>
                                            </div>

                                            {{--  Connect App  --}}
                                            <div class="tab-pane
                                             {{ session()->has('anydesk_callback')               ||     session()->has('anydesk_revoked')        ||
                                                session()->has('anydesk_revoked_fails')          ||     session()->has('anydesk_callback_fails') ||
                                                session()->has('anydesk_refreshToken_refreshed') ||     session()->has('anydesk_refreshToken_fails')
                                                  ? 'active show' : '' }}"
                                                 id="account-vertical-connect" role="tabpanel" aria-labelledby="account-pill-connect" aria-expanded="false">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @if(auth()->user()->hasRole('Admin'))
                                                            @livewire('tenant.test-anydesk-component')
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{--Reset password--}}
                                            <div class="tab-pane fade " id="account-vertical-password" role="tabpanel"
                                                 aria-labelledby="account-pill-password" aria-expanded="false">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @livewire('user-reset-password-profile-component')
                                                    </div>
                                                </div>
                                            </div>

                                            {{--  Tariff  --}}
                                            <div class="tab-pane fade " id="account-vertical-tariff" role="tabpanel"
                                                 aria-labelledby="account-pill-tariff" aria-expanded="false">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @if(auth()->user()->hasRole('Admin'))
                                                            @livewire('tenant.tariff-component',['customer' => null, 'customer_component' => false])
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Two factor authentication --}}
                                            <div class="tab-pane fade" id="account-vertical-info" role="tabpanel"
                                                 aria-labelledby="account-pill-info" aria-expanded="false">

                                                <div class="row gl-mt-3">
                                                    <div class="container text-center">
                                                        @livewire('two-factor-auth-component')
                                                    </div>
                                                    <div class="modal fade" id="confirmPasswordForTwoFactorModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="confirmModalLabel">{{ __('locale.Two Factor Authentication') }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @livewire('confirm-password-for-two-factor-component')
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--  Users component  --}}
                                            <div class="tab-pane {{ session()->has('redirectUsersTab') ? 'active show' : '' }}"
                                                 id="account-vertical-users" role="tabpanel" aria-labelledby="account-pill-users" aria-expanded="false">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @if(auth()->user()->hasRole('Admin'))
                                                            @livewire('tenant.users-component')
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        @endif
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
@section('vendor-scripts')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{asset('vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{asset('vendors/js/extensions/dropzone.min.js')}}"></script>
@endsection

@section('page-scripts')
    <script src="{{asset('js/scripts/pages/page-account-settings.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {

            if (window.location.hash) {
                var hash = window.location.hash.substring(1);
                if (hash == 'account-vertical-tariff') {
                    $(".pills-stacked a[href='#" + hash + "']").click();
                    $(".pills-stacked").hide();
                }
            }

            Livewire.emit('checkForSessionToastr');
            Livewire.emit('checkForCustomerCreated');

            $("#formUserModal").on('hidden.bs.modal', function(){
                livewire.emit('forcedCloseUserModal');
            });

            window.initAPIKeysElements = () => {
                var copyTextareaBtn = document.querySelector('#clipboard_button');
                if (copyTextareaBtn) {
                    copyTextareaBtn.addEventListener('click', function(event) {
                        var copyTextarea = document.querySelector('#plainTextToken');
                        copyTextarea.focus();
                        copyTextarea.select();

                        try {
                            var successful = document.execCommand('copy');
                            var msg = successful ? 'successful' : 'unsuccessful';
                            console.log('Copying text command was ' + msg);
                        } catch (err) {
                            console.log('Oops, unable to copy');
                        }
                    });
                }
            };
            window.livewire.on('initAPIKeysElements', () => {
                initAPIKeysElements();
            });

            window.initTimeMasks = () => {
                var maskBehavior = function (val) {
                    val = val.split(":");
                    return (parseInt(val[0]) > 19)? "HZ:M0" : "H0:M0";
                }
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(maskBehavior.apply({}, arguments), options);
                    },
                    translation: {
                        'H': { pattern: /[0-2]/, optional: false },
                        'Z': { pattern: /[0-3]/, optional: false },
                        'M': { pattern: /[0-5]/, optional: false}
                    }
                };
                $('.time').mask(maskBehavior, spOptions);
            };
            initTimeMasks();
            window.livewire.on('inputTimeMasksHydrate', () => {
                initTimeMasks();
            });

            window.initDatePicker = () => {
                var $locale = "{!! config('app.locale') !!}";
                if ($locale === "en") {
                    $('.pickadate_start,.pickadate_end').pickadate({
                        format: "dd.mm.yyyy",
                        firstDay: 1,
                        clear: false,
                    });
                } else {
                    $('.pickadate_start,.pickadate_end').pickadate({
                        format: "dd.mm.yyyy",
                        firstDay: 1,
                        monthsFull: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
                        weekdaysFull: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
                        weekdaysShort: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
                        today: 'Heute',
                        close: 'Schließen',
                        clear: false,
                    });
                }
            };
            initDatePicker();
            window.livewire.on('daterangepickerHydrate',()=>{
                initDatePicker();
            });

            window.initCurrencyMask = () => {
                $("input[data-type='currency']").on({
                    keyup: function() {
                        formatCurrency($(this));
                    },
                    blur: function() {
                        formatCurrency($(this), "blur");
                    }
                });
                function formatNumber(n) {
                    // format number 1000000 to 1.234.567
                    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                }
                function formatCurrency(input, blur) {
                    // appends $ to value, validates decimal side
                    // and puts cursor back in right position.

                    // get input value
                    var input_val = input.val();

                    // don't validate empty input
                    if (input_val === "") { return; }

                    // original length
                    var original_len = input_val.length;

                    // initial caret position
                    var caret_pos = input.prop("selectionStart");

                    // check for decimal
                    if (input_val.indexOf(",") >= 0) {

                        // get position of first decimal
                        // this prevents multiple decimals from
                        // being entered
                        var decimal_pos = input_val.indexOf(",");

                        // split number by decimal point
                        var left_side = input_val.substring(0, decimal_pos);
                        var right_side = input_val.substring(decimal_pos);

                        // add commas to left side of number
                        left_side = formatNumber(left_side);

                        // validate right side
                        right_side = formatNumber(right_side);

                        // On blur make sure 2 numbers after decimal
                        if (blur === "blur") {
                            right_side += "00";
                        }

                        // Limit decimal to only 2 digits
                        right_side = right_side.substring(0, 2);

                        // join number by .
                        input_val = left_side + "," + right_side;

                    } else {
                        // no decimal entered
                        // add commas to number
                        // remove all non-digits
                        input_val = formatNumber(input_val);
                        // input_val = input_val;

                        // final formatting
                        if (blur === "blur") {
                            input_val += ",00";
                        }
                    }
                    // send updated string to input
                    input.val(input_val);

                    // put caret back in the right position
                    var updated_len = input_val.length;
                    caret_pos = updated_len - original_len + caret_pos;
                    input[0].setSelectionRange(caret_pos, caret_pos);
                }
            }
            initCurrencyMask();
            window.livewire.on('currencyHydrate',()=>{
                initCurrencyMask();
            });

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
                toastr.success('',event.detail.message).css("width","fit-content")
            })
            window.addEventListener('showToastrDelete', event => {
                toastr.warning('',event.detail.message).css("width","fit-content")
            })
            window.addEventListener('showToastrTeamviewerError', event => {
                toastr.error('',event.detail.message).css("width","fit-content")
            })
            window.addEventListener('showToastrError', event => {
                toastr.error('',event.detail.message).css("width","fit-content")
            })

        });

        $(window).blur(function (){
            Livewire.emit('resetPasswordComponent');
        })

        var e = document.getElementById('account-pill-password');
        var observer = new MutationObserver(function (event) {
            if(!$('#account-pill-password').hasClass('active')) {
                Livewire.emit('resetPasswordComponent');
            }
        })
        observer.observe(e, {
            attributes: true,
            attributeFilter: ['class'],
            childList: false,
            characterData: false
        })

        $('#collapseDivForTariff').on('hidden.bs.collapse', function () {
            livewire.emitTo('tenant.tariff-component','cleanVars');
        });

        window.addEventListener('toggleDiv', event => {
            let div = $('#collapseDivForTariff');
            if (div.hasClass('show')) {
                livewire.emitTo('tenant.tariff-component','cleanVars');
                div.collapse("hide");
            } else {
                div.collapse("show");
            }
        });
        window.addEventListener('openToggleDiv', event => {
            $('#collapseDivForTariff').collapse("show");
        })
        window.addEventListener('closeToggleDiv', event => {
            $('#collapseDivForTariff').collapse("hide");
        })

        window.addEventListener('open-confirming-password', event => {
            $("#confirmPasswordForTwoFactorModal").modal('show');
        })

        window.addEventListener('close-confirming-password', event => {
            $("#confirmPasswordForTwoFactorModal").modal('hide');
        })

        //for tariff delete modal
        window.addEventListener('openTariffModalDelete', event => {
            $("#tariffModalDelete").modal('show');
        })

        window.addEventListener('closeTariffModalDelete', event => {
            $("#tariffModalDelete").modal('hide');
        })

        // for user modals
        window.addEventListener('openUserDeleteModal', event => {
            $("#userDeleteModal").modal('show');
        })

        window.addEventListener('closeUserDeleteModal', event => {
            $("#userDeleteModal").modal('hide');
        })

        window.addEventListener('openFormUserModal', event => {
            $("#formUserModal").modal('show');
        })

        window.addEventListener('closeFormUserModal', event => {
            $("#formUserModal").modal('hide');
        })

        window.addEventListener('openDeletePhotoPathModal', event => {
            $("#modalFormDeletePhotoPath").modal('show');
        });

        window.addEventListener('closeDeletePhotoPathModal', event => {
            $("#modalFormDeletePhotoPath").modal('hide');
        });
        $("#modalFormDeletePhotoPath").on('hidden.bs.modal', function(){
            livewire.emit('forcedCloseModal');
        });

    </script>
@yield('tariff_custom_scripts')
@endsection
