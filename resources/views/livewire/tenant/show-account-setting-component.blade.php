@section('title', __('locale.Account Settings'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ mix('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ mix('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
    <link rel="stylesheet" href="{{ mix('assets/css/page_account_setting_css.css') }}" />
    <link rel="stylesheet" href="{{ mix('assets/css/tariff_component_css.css') }}" />
    <link rel="stylesheet" href="{{ mix('assets/vendor/libs/simonwep/dist/themes/monolith.min.css') }}" />
@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ mix('frontend/css/loading_states_awesome.css') }}">
    <link rel="stylesheet" href="{{ mix('frontend/css/users-component_css.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/css/company_component_css.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ mix('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ mix('assets/vendor/libs/simonwep/dist/pickr.min.js') }}"></script>
@endsection

<section id="page-account-settings">
    <div class="row">
        @php
            $pageHash = '#account-vertical-general';

            if (\Request::route()->getName() == 'pages.tariffs') {
                $pageHash = '#account-vertical-tariff';
            }
        @endphp

        <div x-data="{ contentShow: ['#basic', '#billing', '#account-vertical-tariff', '#account-vertical-general', '#account-vertical-connect', '#account-vertical-password', '#account-vertical-info', '#account-vertical-users'].includes(window.location.hash) ? window.location.hash : '{{ $pageHash }}' }">
            <div id="wizard-create-deal" class="bs-stepper vertical mt-2 linear" style="min-height: calc(100vh - 120px) !important;">
                <div class="bs-stepper-header" style="min-width: 20rem;" x-show="!(contentShow == '#account-vertical-tariff')">
                    @if ($isAdminRole)
                        <div class="step" :class="contentShow == '#basic' && 'active'">
                            <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#basic'">
                                <span class="bs-stepper-circle">
                                    <i class="bx bx-pin"></i>
                                </span>

                                <span class="bs-stepper-label">
                                    <a class="d-flex align-items-center" data-toggle="pill" href="#basic" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                        {{ __('locale.Basic') }}
                                    </a>
                                </span>
                            </button>
                        </div>
                    @endif

                    @if (auth()->user()->locale == 'en')
                        <div class="step" :class="contentShow == '#account-vertical-general' && 'active'">
                            <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#account-vertical-general'">
                                <span class="bs-stepper-circle">
                                    <i class="bx bx-cog"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <a class="d-flex align-items-center {{ !$isAdminRole ? 'active show' : '' }}" id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true" @click="contentShow = $el.getAttribute('href')">
                                        {{ __('locale.General') }}
                                    </a>
                                </span>
                            </button>
                        </div>

                        @if ($isAdminRole)
                            <div class="step" :class="contentShow == '#billing' && 'active'">
                                <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#billing'">
                                    <span class="bs-stepper-circle">
                                        <i class="bx bx-analyse"></i>
                                    </span>

                                    <span class="bs-stepper-label">
                                        <a class="d-flex align-items-center" data-toggle="pill" href="#billing" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                            {{ __('locale.Billing') }}
                                        </a>
                                    </span>
                                </button>
                            </div>

                            <div class="step" :class="contentShow == '#account-vertical-users' && 'active'">
                                <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#account-vertical-users'">
                                    <span class="bs-stepper-circle">
                                        <i class="bx bx-group"></i>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <a class="d-flex align-items-center {{ session()->has('redirectUsersTab') ? 'active show' : '' }}" id="account-pill-users" data-toggle="pill" href="#account-vertical-users" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                            {{ __('locale.User Management') }}
                                        </a>
                                    </span>
                                </button>
                            </div>

                            <div class="step" :class="contentShow == '#account-vertical-connect' && 'active'">
                                <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#account-vertical-connect'">
                                    <span class="bs-stepper-circle">
                                        <i class="bx bx-sitemap"></i>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <a class="d-flex align-items-center {{ session()->has('redirectUsersTab') ? '' : 'active show' }}" id="account-pill-connect" data-toggle="pill" href="#account-vertical-connect" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                            {{ __('locale.Connect Teamviewer App') }}
                                        </a>
                                    </span>
                                </button>
                            </div>
                        @endif

                        <div class="step" :class="contentShow == '#account-vertical-password' && 'active'">
                            <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#account-vertical-password'">
                                <span class="bs-stepper-circle">
                                    <i class="bx bx-lock"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <a class="d-flex align-items-center" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                        {{ __('locale.Reset Password') }}
                                    </a>
                                </span>
                            </button>
                        </div>

                        @if ($isAdminRole)
                            <div class="step" :class="contentShow == '#account-vertical-tariff' && 'active'" x-show="contentShow == '#account-vertical-tariff'">
                                <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#account-vertical-tariff'">
                                    <span class="bs-stepper-circle">
                                        <i class="bx bx-wallet"></i>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <a class="d-flex align-items-center" id="account-pill-tariff" data-toggle="pill" href="#account-vertical-tariff" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                            {{ __('locale.Tariffs') }}
                                        </a>
                                    </span>
                                </button>
                            </div>
                        @endif

                        <div class="step" :class="contentShow == '#account-vertical-info' && 'active'">
                            <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#account-vertical-info'">
                                <span class="bs-stepper-circle">
                                    <i class="bx bx-info-circle"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <a class="d-flex align-items-center" id="account-pill-info" data-toggle="pill" href="#account-vertical-info" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                        {{ __('locale.Two Factor Authentication (2FA)') }}
                                    </a>
                                </span>
                            </button>
                        </div>
                        <div class="step" :class="contentShow == '#company-logos' && 'active'">
                            <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#company-logos'">
                                <span class="bs-stepper-circle">
                                    <i class="bx bx-home-alt"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <a class="d-flex align-items-center" id="account-pill-company-logos" data-toggle="pill" href="#company-logos" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                        {{ __('locale.Company logos') }}
                                    </a>
                                </span>
                            </button>
                        </div>
                    @else
                        <div class="step" :class="contentShow == '#account-vertical-general' && 'active'">
                            <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#billing'">
                                <span class="bs-stepper-circle">
                                    <i class="bx bx-cog"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <a class="d-flex align-items-center
                                        {{ session()->has('anydesk_callback')                ||     session()->has('anydesk_revoked')        ||
                                            session()->has('anydesk_revoked_fails')          ||     session()->has('anydesk_callback_fails') ||
                                            session()->has('anydesk_refreshToken_refreshed') ||     session()->has('anydesk_refreshToken_fails') ||
                                            session()->has('redirectUsersTab')
                                            ? '' : 'active show' }}"
                                        id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true" @click="contentShow = $el.getAttribute('href')">
                                        {{ __('locale.General') }}
                                    </a>
                                </span>
                            </button>
                        </div>

                        @if ($isAdminRole)
                            <div class="step" :class="contentShow == '#account-vertical-users' && 'active'">
                                <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#account-vertical-users'">
                                    <span class="bs-stepper-circle">
                                        <i class="bx bx-group"></i>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <a class="d-flex align-items-center {{ session()->has('redirectUsersTab') ? 'active show' : '' }}" id="account-pill-users" data-toggle="pill" href="#account-vertical-users" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                            {{ __('locale.User Management') }}
                                        </a>
                                    </span>
                                </button>
                            </div>

                            <div class="step" :class="contentShow == '#billing' && 'active'">
                                <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#billing'">
                                    <span class="bs-stepper-circle">
                                        <i class="bx bx-analyse"></i>
                                    </span>

                                    <span class="bs-stepper-label">
                                        <a class="d-flex align-items-center" data-toggle="pill" href="#billing" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                            {{ __('locale.Billing') }}
                                        </a>
                                    </span>
                                </button>
                            </div>

                            <div class="step" :class="contentShow == '#account-vertical-connect' && 'active'">
                                <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#account-vertical-connect'">
                                    <span class="bs-stepper-circle">
                                        <i class="bx bx-sitemap"></i>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <a class="d-flex align-items-center
                                            {{ session()->has('anydesk_callback')                ||     session()->has('anydesk_revoked')        ||
                                                session()->has('anydesk_revoked_fails')          ||     session()->has('anydesk_callback_fails') ||
                                                session()->has('anydesk_refreshToken_refreshed') ||     session()->has('anydesk_refreshToken_fails')
                                                    ? 'active show' : '' }}"
                                            id="account-pill-connect" data-toggle="pill" href="#account-vertical-connect" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                            {{ __('locale.Connect Teamviewer App') }}
                                        </a>
                                    </span>
                                </button>
                            </div>
                        @endif

                        <div class="step" :class="contentShow == '#account-vertical-password' && 'active'">
                            <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#account-vertical-password'">
                                <span class="bs-stepper-circle">
                                    <i class="bx bx-lock"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <a class="d-flex align-items-center" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                        {{ __('locale.Reset Password') }}
                                    </a>
                                </span>
                            </button>
                        </div>

                        @if ($isAdminRole)
                            <div class="step" :class="contentShow == '#account-vertical-tariff' && 'active'" x-show="contentShow == '#account-vertical-tariff'">
                                <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#account-vertical-tariff'">
                                    <span class="bs-stepper-circle">
                                        <i class="bx bx-wallet"></i>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <a class="d-flex align-items-center" id="account-pill-tariff" data-toggle="pill" href="#account-vertical-tariff" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                            {{ __('locale.Tariffs') }}
                                        </a>
                                    </span>
                                </button>
                            </div>
                        @endif

                        <div class="step" :class="contentShow == '#account-vertical-info' && 'active'">
                            <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#account-vertical-info'">
                                <span class="bs-stepper-circle">
                                    <i class="bx bx-info-circle"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <a class="d-flex align-items-center" id="account-pill-info" data-toggle="pill" href="#account-vertical-info" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                        {{ __('locale.Two Factor Authentication (2FA)') }}
                                    </a>
                                </span>
                            </button>
                        </div>

                        <div class="step" :class="contentShow == '#company-logos' && 'active'">
                            <button type="button" class="step-trigger" aria-selected="true" @click="contentShow = '#company-logos'">
                                <span class="bs-stepper-circle">
                                    <i class="bx bx-home-alt"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <a class="d-flex align-items-center" id="account-pill-info" data-toggle="pill" href="#company-logos" aria-expanded="false" @click="contentShow = $el.getAttribute('href')">
                                        {{ __('locale.Company logos') }}
                                    </a>
                                </span>
                            </button>
                        </div>
                        @endif
                </div>
                <div class="bs-stepper-content">
                    <div>
                        @if ($isAdminRole)
                            <div id="#basic" role="tabpanel" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                <div id="#tab-basic" role="tabpanel" aria-labelledby="account-pill-connect" aria-expanded="true">

                                    {{--  company logo from basic tab  --}}
                                    {{--<div class="row">
                                        <div class="col">
                                            <label class="form-label" for="input-logo" class="">{{ __('locale.Logo') }}</label>
                                            <br/>
                                            <div class="d-flex align-items-start align-items-sm-center gap-4 media">
                                                @if($logo)
                                                    <img class="rounded mr-75" src="{{ $logo->temporaryUrl() }}" alt="Logo"
                                                        height="60" width="60" style="">
                                                @elseif($prevLogo)
                                                    <img class="rounded mr-75" src="{{ url($prevLogo) }}" alt="Logo" height="60"
                                                        width="60">
                                                @else
                                                    --}}{{-- <img class="rounded mr-75" src="{{ mix('assets/images/backgrounds/user_icon.png') }}" alt="Logo" height="60" width="60"> --}}{{--
                                                    <i class="bx bx-buildings bx-lg"></i>
                                                @endif

                                                <div class="button-wrapper">
                                                    <div class="media-body mt-25">
                                                        <div
                                                            class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                                                            <label class="form-label" for="input-logo" class="btn btn-sm btn-dark me-2 mt-3"
                                                                tabindex="0">
                                                                <span>{{ __('locale.Upload new photo') }}</span>
                                                                <div class="text-nowrap" style="margin-left: 5px;" wire:loading
                                                                    wire:target="logo">
                                                                    <div style="color: #a779e9;" class="la-line-scale la-sm">
                                                                        <div></div>
                                                                        <div></div>
                                                                        <div></div>
                                                                        <div></div>
                                                                        <div></div>
                                                                    </div>
                                                                </div>

                                                                <input type="file" id="input-logo" hidden
                                                                    wire:model.debounce.1100ms="logo" wire:loading="disabled">
                                                            </label>

                                                            <button id="logo-reset" type="button"
                                                                    class="btn btn-sm btn-outline-dark account-image-reset mt-3"
                                                                    wire:click="onResetButton">
                                                                <i class="bx bx-reset d-block d-sm-none"></i>
                                                                <span class="d-none d-sm-block">{{ __('locale.Reset') }}</span>
                                                            </button>
                                                        </div>
                                                        <p class="text-muted ml-1 mt-50">
                                                            <small>{{ __('locale.Allowed JPG, GIF or PNG. Max size of 1MB') }}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                                <p>
                                                    @error('logo') <span class="error"
                                                                        style="color: #ff0000">{{ $message }}</span> @enderror
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <br/>--}}

                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label" for="name">{{ __('locale.Company Name') }}</label>
                                            <input type="text" id="name" class="form-control"
                                                wire:model="name"> {{-- placeholder="{{ __('locale.Name') }}" --}}
                                            @error('name') <span class="error"
                                                                style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="col-6">
                                            <label class="form-label" for="address">{{ __('locale.Address') }}</label>
                                            <input type="text" id="address" class="form-control" wire:model="address">
                                            @error('address') <span class="error"
                                                                    style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row pt-1">
                                        <div class="col-6">
                                            <label class="form-label" for="zip">{{ __('locale.ZIP') }}</label>
                                            <input type="text" id="zip" class="form-control" wire:model="zip">
                                            @error('zip') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="city">{{ __('locale.City') }}</label>
                                            <input type="text" id="city" class="form-control" wire:model="city">
                                            @error('city') <span class="error"
                                                                style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row pt-1">
                                        <div class="col-6">
                                            <label class="form-label" for="country">{{ __('locale.Country') }}</label>
                                            <input type="text" id="country" class="form-control" wire:model="country">
                                            @error('country') <span class="error"
                                                                    style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="email">{{ __('locale.Company Email') }}</label>
                                            <input type="email" id="email" class="form-control" wire:model="email">
                                            @error('email') <span class="error"
                                                                style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row pt-1">
                                        <div class="col-6">
                                            <label class="form-label" for="phone">{{ __('locale.Phone number') }}</label>
                                            <input type="text" id="phone" class="form-control" wire:model="phone">
                                            @error('phone') <span class="error"
                                                                style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-6">
                                            <fieldset>
                                                <label class="form-label" for="website">{{ __('locale.Website') }}</label>
                                                <div class="input-group d-flex">
                                                    {{-- <div class="input-group-prepend"> --}}
                                                    {{-- <span class="input-group-text" id="url">https://</span> --}}
                                                    {{-- </div> --}}
                                                    <input type="text" wire:model="website" class="form-control" id="website"
                                                        placeholder="" aria-describedby="basic-addon1">
                                                </div>
                                            </fieldset>

                                            {{-- <label class="form-label" for="website">{{ __('locale.Website') }}</label>--}}
                                            {{-- <input type="url" id="website" class="form-control" wire:model="website">--}}
                                            @error('website') <span class="error"
                                                                    style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="row pt-1">
                                        <div class="col-6">
                                            <label class="form-label" for="notes">{{ __('locale.Notes') }}</label>
                                            <textarea name="" id="notes" cols="40" rows="3" class="form-control" maxlength="500" wire:model="notes"></textarea>
                                            @error('notes') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div> --}}

                                    <div class="bs-stepper-footer">
                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                            <button class="btn btn-dark glow mb-sm-0 mr-sm-1 my-1" wire:loading.attr="disabled"
                                                    wire:click="save">
                                                <span>{{ __('locale.Save') }}</span>
                                                <span style="margin-left: 5px;" wire:loading wire:target="save">
                                                    <div style="color: #F2F2F2;" class="la-line-scale la-sm">
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                    </div>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="#billing" role="tabpanel" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                <div id="#tab-billing" role="tabpanel" aria-labelledby="account-pill-connect" aria-expanded="false">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label" for="billing_address">{{ __('locale.Invoice Address') }}</label>
                                            <input type="text" id="billing_address" class="form-control"
                                                wire:model="billing_address">
                                            @error('billing_address') <span class="error"
                                                                            style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="payment">{{ __('locale.Payment Method') }}</label>
                                            <input type="text" id="payment" class="form-control" wire:model="payment">
                                            @error('payment') <span class="error"
                                                                    style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label" for="tax_number">{{ __('locale.VAT ID / Tax Number') }}</label>
                                            <input type="text" id="tax_number" class="form-control bic" wire:model="tax_number">
                                            @error('tax_number') <span class="error"
                                                                    style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-6" wire:ignore.self>
                                            <label class="form-label" for="iban">IBAN</label>
                                            <input type="text" id="iban" class="form-control iban" wire:model="iban"> {{----}}
                                            @error('iban') <span class="error"
                                                                style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label" for="bic">BIC</label>
                                            <input type="text" id="bic" class="form-control bic" wire:model="bic">
                                            @error('bic') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <hr style="font-weight: bolder; width: 100%;left: 0;margin: 2rem 0;">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label" for="contact">{{ __('locale.Contact') }}</label>
                                            <input type="text" id="contact" class="form-control" wire:model="contact">
                                            @error('contact') <span class="error"
                                                                    style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="contact_email">{{ __('locale.Contact Email') }}</label>
                                            <input type="text" id="contact_email" class="form-control" wire:model="contact_email">
                                            @error('contact_email') <span class="error"
                                                                        style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="bs-stepper-footer">
                                    <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                        <button class="btn btn-dark glow mb-sm-0 mr-sm-1 my-1" wire:loading.attr="disabled"
                                                wire:click="save">
                                            <span>{{ __('locale.Save') }}</span>
                                            <span style="margin-left: 5px;" wire:loading wire:target="save">
                                                <div style="color: #F2F2F2;" class="la-line-scale la-sm">
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                </div>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Photo Modal -->
                            <div class="modal fade" id="modalFormDeleteLogo" tabindex="-1" aria-labelledby="deleteLogoModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="deleteLogoModalLabel">{{ __('locale.Totally delete photo profile') }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                                    wire:click="closeDeletePhotoModal">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h3>{{ __('locale.Are you sure?') }}</h3>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                    wire:click="closeDeletePhotoModal">{{ __('locale.Cancel') }}</button>
                                            <button type="button" class="btn btn-dark delete-company-logo" data-logo-type="">{{ __('locale.Yes') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (auth()->user()->locale == 'en')
                            @if ($isAdminRole)
                                {{--  Connect App  --}}
                                <div class="{{ session()->has('redirectUsersTab') ? '' : 'active show' }}" id="#account-vertical-connect" role="tabpanel" aria-labelledby="account-pill-connect" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                    @if($isAdminRole)
                                        @livewire('tenant.test-anydesk-component')
                                    @endif
                                </div>
                            @endif

                            {{-- General --}}
                            <div class="" id="#account-vertical-general" role="tabpanel" aria-labelledby="account-pill-general" aria-expanded="true" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                <div class="row">
                                    <div class="col-12">
                                        @livewire('user-name-email-profile-component')
                                    </div>
                                </div>
                            </div>

                            {{--Reset password--}}
                            <div class="" id="#account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                <div class="row">
                                    <div class="col-12">
                                        @livewire('user-reset-password-profile-component')
                                    </div>
                                </div>
                            </div>

                            @if ($isAdminRole)
                                {{--  Tariff  --}}
                                <div class="" id="#account-vertical-tariff" role="tabpanel" aria-labelledby="account-pill-tariff" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                    <div class="row">
                                        <div class="col-12">
                                            @if($isAdminRole)
                                                @livewire('tenant.tariff-component')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Two factor authentication --}}
                            <div class="" id="#account-vertical-info" role="tabpanel" aria-labelledby="account-pill-info" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
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

                                @if ($isAdminRole)
                                    {{-- Company logos --}}
                                    <div id="#company-logos" role="tabpanel"
                                         aria-labelledby="account-pill-company-logos" aria-expanded="true"
                                         x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                        <div class="row">
                                            <div class="col-12">
                                                @livewire('company-logo-component')
                                            </div>
                                        </div>
                                    </div>
                                {{--  Users component  --}}
                                <div class="{{ session()->has('redirectUsersTab') ? 'active show' : '' }}"id="#account-vertical-users" role="tabpanel" aria-labelledby="account-pill-users" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                    <div class="row">
                                        <div class="col-12">
                                            @if($isAdminRole)
                                                @livewire('tenant.users-component')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            {{-- General --}}
                            <div class="{{ session()->has('anydesk_callback')               ||     session()->has('anydesk_revoked')        ||
                                    session()->has('anydesk_revoked_fails')          ||     session()->has('anydesk_callback_fails') ||
                                    session()->has('anydesk_refreshToken_refreshed') ||     session()->has('anydesk_refreshToken_fails') ||
                                    session()->has('redirectUsersTab') ? '' : 'active show' }}"
                                    id="#account-vertical-general" role="tabpanel" aria-labelledby="account-pill-general" aria-expanded="true" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                <div class="row">
                                    <div class="col-12">
                                        @livewire('user-name-email-profile-component')
                                    </div>
                                </div>
                            </div>

                            @if ($isAdminRole)
                                {{--  Connect App  --}}
                                <div class="{{ session()->has('anydesk_callback')               ||     session()->has('anydesk_revoked')        ||
                                    session()->has('anydesk_revoked_fails')          ||     session()->has('anydesk_callback_fails') ||
                                    session()->has('anydesk_refreshToken_refreshed') ||     session()->has('anydesk_refreshToken_fails')
                                        ? 'active show' : '' }}"
                                        id="#account-vertical-connect" role="tabpanel" aria-labelledby="account-pill-connect" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                        @if($isAdminRole)
                                            @livewire('tenant.test-anydesk-component')
                                        @endif
                                </div>
                            @endif

                            {{--Reset password--}}
                            <div class="" id="#account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                <div class="row">
                                    <div class="col-12">
                                        @livewire('user-reset-password-profile-component')
                                    </div>
                                </div>
                            </div>

                            @if ($isAdminRole)
                                {{--  Tariff  --}}
                                <div class="" id="#account-vertical-tariff" role="tabpanel" aria-labelledby="account-pill-tariff" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                    <div class="row">
                                        <div class="col-12">
                                            @if($isAdminRole)
                                                @livewire('tenant.tariff-component',['customer' => null, 'customer_component' => false])
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Two factor authentication --}}
                            <div class="" id="#account-vertical-info" role="tabpanel" aria-labelledby="account-pill-info" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
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

                            @if ($isAdminRole)
                                    {{-- Company logos --}}
                                    <div id="#company-logos" role="tabpanel"
                                         aria-labelledby="account-pill-company-logos" aria-expanded="true"
                                         x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                        <div class="row">
                                            <div class="col-12">
                                                @livewire('company-logo-component')
                                            </div>
                                        </div>
                                    </div>

                                    {{--  Users component  --}}
                                <div class="{{ session()->has('redirectUsersTab') ? 'active show' : '' }}" id="#account-vertical-users" role="tabpanel" aria-labelledby="account-pill-users" aria-expanded="false" x-show="contentShow == $el.getAttribute('id')" x-cloak>
                                    <div class="row">
                                        <div class="col-12">
                                            @if($isAdminRole)
                                                @livewire('tenant.users-component')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('page-script')
    <script defer src="{{ mix('assets/js/jqBootstrapValidation.js') }}"></script>
    <script defer src="{{ mix('assets/vendor/libs/jquery-mask-plugin/dist/jquery.mask.min.js') }}"></script>

    <script defer type="text/javascript">
        $(document).ready(function() {
            var fromCallback = '{{ $fromCallback }}';

            function centeredPopup(e) {
                e.preventDefault();

                let self    = $(this),
                    url     = self.data('win-url'),
                    winName = self.data('win-name'),
                    width   = self.data('win-width'),
                    height  = self.data('win-height'),
                    scroll  = self.data('win-scroll');

                let leftPosition = (screen.width) ? (screen.width - width) / 2 : 0,
                    topPosition = (screen.height) ? (screen.height - height) / 2 : 0;

                let settings = 'height=' + width + ',width=' + width + ',top=' + topPosition + ',left=' + leftPosition + ',scrollbars=' + scroll + ',resizable';

                let popupWindow = window.open(url, winName, settings);

                let timer = setInterval(function() {
                    if (popupWindow.closed) {
                        clearInterval(timer);

                        window.location.reload();
                    }
                }, 1000);
            }

            if (fromCallback) {
                window.self.close();
            }

            $(document).find(".open-window").on("click", centeredPopup);

            window.colorPicker = () => {
                let colorPickrInput = $("#favcolor"),
                    colorPickr = Pickr.create({
                        el: '#color-picker-monolith',
                        theme: 'monolith',
                        default: colorPickrInput.val(),
                        swatches: [
                            'rgba(102, 108, 232, 1)',
                            'rgba(40, 208, 148, 1)',
                            'rgba(255, 73, 97, 1)',
                            'rgba(255, 145, 73, 1)',
                            'rgba(30, 159, 242, 1)'
                        ],
                        components: {
                            // Main components
                            preview: true,
                            opacity: true,
                            hue: true,

                            // Input / output Options
                            interaction: {
                                hex: true,
                                rgba: true,
                                hsla: true,
                                hsva: true,
                                cmyk: true,
                                input: true,
                                clear: false,
                                save: true
                            }
                        }
                    });

                colorPickr
                    .on("clear", function(instance) {
                        @this.set('tariffSelectedColor', colorPickrInput.val());
                    })
                    .on("cancel", function(instance) {
                        currentColor = instance.getSelectedColor().toHEXA().toString();

                        @this.set('tariffSelectedColor', currentColor);
                    })
                    .on("save", function(color, instance) {
                        currentColor = color.toHEXA().toString();

                        @this.set('tariffSelectedColor', currentColor);

                        instance.hide();
                    });
            };

            window.livewire.on('colorPickerHydrate', () => {
                colorPicker();
            });

            /*
            // language select
            let languageselect = $("#languageselect2").select2({
                dropdownAutoWidth: true,
                width: '100%'
            });
            // music select
            let musicselect = $("#musicselect2").select2({
                dropdownAutoWidth: true,
                width: '100%'
            });
            // movies select
            let moviesselect = $("#moviesselect2").select2({
                dropdownAutoWidth: true,
                width: '100%'
            });
            // birthdate date
            $('.birthdate-picker').pickadate({
                format: 'mmmm, d, yyyy'
            });
            */

            (function (window, document, $) {
                'use strict';
                // Input, Select, Textarea validations except submit button
                $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
            })(window, document, jQuery);

            if (window.location.hash) {
                var hash = window.location.hash.substring(1);
                if (hash == 'account-vertical-tariff') {
                    $(".pills-stacked a[href='#" + hash + "']").click();
                    $(".pills-stacked").hide();
                }
            }

            Livewire.emit('checkForSessionToastr');
            Livewire.emit('checkForCustomerCreated');
            Livewire.emit('checkForTeamviewerModelShow');

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
                    flatpickr($('.pickadate_start,.pickadate_end'), {});
                } else {
                    flatpickr($('.pickadate_start,.pickadate_end'), {
                        "locale": "de"
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
            };

            window.addEventListener('showToastrSuccess', event => {
                toastr.success('',event.detail.message).css("width","fit-content")
            });
            window.addEventListener('showToastrTeamviewerError', event => {
                toastr.options.enableHtml = true;

                toastr.error('', event.detail.message).css("width", "fit-content")
            });
            window.addEventListener('showToastrError', event => {
                toastr.error('',event.detail.message).css("width","fit-content")
            });
            window.addEventListener('showTeamviewerManual', event => {
                if (!$("#modalManual").data('bs.modal')?._isShown) {
                    $("#modalManual").modal("show");
                }
            });

            // My Company page js start.
            window.addEventListener('searchUpdate', event => {
                const url = new URL(window.location);

                url.searchParams.set('search', event.detail.search);

                window.history.pushState(null, '', url.toString());
            });

            window.initInputMasks = () => {
                $('.iban').mask('SS00-0000-0000-0000-0000-00');
                $('.bic').mask('0000');
            };
            initInputMasks();
            window.livewire.on('inputMasksHydrate', () => {
                initInputMasks();
            });

            window.addEventListener('loadIbanBicInputs', event => {
                $('.iban').val = event.detail.iban;
                $('.bic').val = event.detail.bic;
            });

            window.addEventListener('focusErrorInput', event => {
                var $field = '#' + event.detail.field;
                $($field).focus()
            });
            // My Company page js end.
        });

        // My Company page js start.
        window.addEventListener("openDeleteLogoModal", event => {
            let logo_type = event.detail.logo_type || "";
            $(".delete-company-logo").attr("data-logo-type", logo_type);
            $("#modalFormDeleteLogo").modal("show");
        });

        window.addEventListener('closeDeleteLogoModal', event => {
            $("#modalFormDeleteLogo").modal('hide');
        });

        window.addEventListener('openUserDeleteModalId', event => {
            $("#userDeleteModal-" + event.detail.userId).modal('show');
        });

        window.addEventListener('closeUserDeleteModalId', event => {
            $("#userDeleteModal-" + event.detail.userId).modal('hide');
        });
        // My Company page js end.

        var e = document.getElementById('account-pill-password');
        var observer = new MutationObserver(function (event) {})
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
        window.addEventListener('pingAndRefreshTVConnection', event => {
            Livewire.emit('pingAndRefreshTVConnection');
        });
        $("#modalFormDeletePhotoPath").on('hidden.bs.modal', function(){
            livewire.emit('forcedCloseModal');
        });
        $('.delete-company-logo').on('click',function(){
            Livewire.emitTo('company-logo-component', 'deletePhoto', $(this).attr('data-logo-type'));
        });
    </script>
@endsection
