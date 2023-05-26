@if(!is_null($customer))
@section('title', __('locale.Edit Customer'))
@else
@section('title', __('locale.New Customer'))
@endif

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{ mix('assets/vendor/libs/select2/select2.css') }}">
<link rel="stylesheet" type="text/css" href="{{ mix('assets/vendor/libs/apex-charts/apex-charts.css') }}">
{{--
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/pickadate/pickadate.css') }}">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css"> --}}
<link rel="stylesheet" href="{{ mix('assets/css/show_customer_component_css.css') }}">
@endsection

@section('custom_css')
<link rel="stylesheet" href="{{asset('/frontend/css/tariff_component_css.css?v=').time()}}">
<link rel="stylesheet" href="{{asset('frontend/css/contact_component_css.css?v=').time()}}">
<link rel="stylesheet" href="{{asset('frontend/css/loading_states_awesome.css')}}">
@endsection

@yield('page_custom_css')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center"
        style="text-shadow: 1px 1px 2px #7DA0B1;">
        @if(!is_null($customer))
            <h3>{{ $customer->customer_name }}&nbsp;
                @if (!is_null($customer))
                    <a class="nav-link" style="padding: unset;display: inline;"
                       href="{{ route('customer.connections', $customer->bdgogid). '?ff=1' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-external-link">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                    </a>
                @endif
            </h3>

            {{-- Customer Activate/Deactivate toggle switch --}}
            <label class="switch">
                <input type="checkbox" wire:model="active" class="switch-input" />
                <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                    <span class="switch-off"></span>
                </span>
                <span class="switch-label">{{$active ? __('locale.Activated') : __('locale.Deactivated')}}</span>
            </label>
        @else
            <h3>{{ __('locale.New Customer') }}</h3>
        @endif


    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="custom-top-menu">
                @if($tab_selected == 'general')
                <div class="flex-row d-flex">
                    <div class="col-auto mr-auto d-flex w-100  responsive-icons">
                        @role('Admin')
                            @can('customer_statistic')
                            <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')"> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-activity">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                </svg>
                                <span class="f-18">{{ __('locale.Statistics') }}</span>
                            </button>
                            @endcan
                        @endrole
                        @can('customer_general')
                            <button class="btn btn-dark" wire:click="$set('tab_selected', 'general')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-settings">
                                    <circle cx="12" cy="12" r="3" />
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                </svg>
                                <span class="f-18">{{ __('locale.General') }}</span>
                            </button>
                        @endcan

                        @role('Admin')
                            @can('customer_billing')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'billing')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                        <polyline points="10 9 9 9 8 9" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Billing') }}</span>
                                </button>
                            @endcan

                            @can('customer_tariff')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'tariff')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-tag">
                                        <path
                                            d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                        <line x1="7" y1="7" x2="7.01" y2="7" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Tariffs') }}</span>
                                </button>
                            @endcan
                        @endrole

                        @can('customer_contact')
                            <button class="btn btn-light-info" wire:click="$set('tab_selected', 'contact')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-phone">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg>
                                <span class="f-18">{{ __('locale.Contacts') }}</span>
                            </button>
                        @endcan

                        @role('Admin')
                            @can('customer_device')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'device')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-tablet">
                                        <rect x="4" y="2" width="16" height="20" rx="2" ry="2" />
                                        <line x1="12" y1="18" x2="12.01" y2="18" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Devices') }}</span>
                                </button>
                            @endcan
                        @endrole
                        @if(!auth()->user()->hasRole('Admin'))
                            @can('customer_statistic')
                            <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')"> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-activity">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                </svg>
                                <span class="f-18">{{ __('locale.Statistics') }}</span>
                            </button>
                            @endcan
                        @endif
                        @role('Admin')
                            @can('customer_documents')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'documents')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                                    </svg>
                                    <span class="f-18">{{ __('locale.Documents') }}</span>
                                </button>
                            @endcan
                        @endrole
                    </div>
                    {{-- <div class="col text-right mr-2">
                        @if(!is_null($customer))
                        <a href="{{ route('customer.connections', ['customer' => $customer]) }}"
                            class="btn btn-sm btn-outline-dark" wire:key="connections-link">
                            <i class="bx bx-list-ul"></i>
                            <span class="f-18">{{ __('locale.Connections') }}</span>
                        </a>
                        @endif
                    </div> --}}
                </div>
                @elseif($tab_selected == 'billing')
                    <div class="flex-row d-flex">
                        <div class="col-auto mr-auto d-flex w-100   responsive-icons">
                            @role('Admin')
                                @can('customer_statistic')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-activity">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Statistics') }}</span>
                                </button>
                                @endcan
                            @endrole
                            @can('customer_general')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'general')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-settings">
                                        <circle cx="12" cy="12" r="3" />
                                        <path
                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.General') }}</span>
                                </button>
                            @endcan

                            @can('customer_billing')
                                <button class="btn btn-dark" wire:click="$set('tab_selected', 'billing')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                        <polyline points="10 9 9 9 8 9" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Billing') }}</span>
                                </button>
                            @endcan

                            @can('customer_tariff')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'tariff')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-tag">
                                        <path
                                            d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                        <line x1="7" y1="7" x2="7.01" y2="7" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Tariffs') }}</span>
                                </button>
                            @endcan

                            @can('customer_contact')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'contact')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-phone">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Contacts') }}</span>
                                </button>
                            @endcan

                            @role('Admin')
                                @can('customer_device')
                                    <button class="btn btn-light-info" wire:click="$set('tab_selected', 'device')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-tablet">
                                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2" />
                                            <line x1="12" y1="18" x2="12.01" y2="18" />
                                        </svg>
                                        <span class="f-18">{{ __('locale.Devices') }}</span>
                                    </button>
                                @endcan
                            @endrole
                            @if(!auth()->user()->hasRole('Admin'))
                                @can('customer_statistic')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-activity">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Statistics') }}</span>
                                </button>
                                @endcan
                            @endif
                            @role('Admin')
                                @can('customer_documents')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'documents')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                                    </svg>
                                    <span class="f-18">{{ __('locale.Documents') }}</span>
                                </button>
                                @endcan
                            @endrole

                            {{-- <a href="" class="ml-1" style="color: #7DA0B1" wire:click.prevent="fillFromGeneral">
                                <small><i><strong>( {{ __('locale.fill from General') }} )</strong></i></small>
                            </a> --}}
                        </div>
                        {{-- <div class="col text-right mr-2">
                            @if(!is_null($customer))
                            <a href="{{ route('customer.connections', ['customer' => $customer]) }}"
                                class="btn btn-sm btn-outline-dark" wire:key="connections-link">
                                <i class="bx bx-list-ul"></i>
                                <span class="f-18">{{ __('locale.Connections') }}</span>
                            </a>
                            @endif
                        </div> --}}
                    </div>
                @elseif($tab_selected == 'tariff')
                    <div class="flex-row d-flex">
                        <div class="col-auto mr-auto d-flex w-100  responsive-icons">
                            @role('Admin')
                                @can('customer_statistic')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-activity">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Statistics') }}</span>
                                </button>
                                @endcan
                            @endrole
                            @can('customer_general')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'general')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-settings">
                                        <circle cx="12" cy="12" r="3" />
                                        <path
                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.General') }}</span>
                                </button>
                            @endcan

                            @can('customer_billing')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'billing')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                        <polyline points="10 9 9 9 8 9" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Billing') }}</span>
                                </button>
                            @endcan

                            @can('customer_tariff')
                                <button class="btn btn-dark" wire:click="$set('tab_selected', 'tariff')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-tag">
                                        <path
                                            d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                        <line x1="7" y1="7" x2="7.01" y2="7" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Tariffs') }}</span>
                                </button>
                            @endcan

                            @can('customer_contact')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'contact')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-phone">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Contacts') }}</span>
                                </button>
                            @endcan

                            @role('Admin')
                                @can('customer_device')
                                    <button class="btn btn-light-info" wire:click="$set('tab_selected', 'device')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-tablet">
                                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2" />
                                            <line x1="12" y1="18" x2="12.01" y2="18" />
                                        </svg>
                                        <span class="f-18">{{ __('locale.Devices') }}</span>
                                    </button>
                                @endcan
                            @endrole
                            @if(!auth()->user()->hasRole('Admin'))
                                @can('customer_statistic')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-activity">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Statistics') }}</span>
                                </button>
                                @endcan
                            @endif
                            @role('Admin')
                                @can('customer_documents')
                                    <button class="btn btn-light-info" wire:click="$set('tab_selected', 'documents')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-file-text">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                                        </svg>
                                        <span class="f-18">{{ __('locale.Documents') }}</span>
                                    </button>
                                @endcan
                            @endrole

                        </div>
                        {{-- <div class="col text-right mr-2">
                            @if(!is_null($customer))
                            <a href="{{ route('customer.connections', ['customer' => $customer]) }}"
                                class="btn btn-sm btn-outline-dark" wire:key="connections-link">
                                <i class="bx bx-list-ul"></i>
                                <span class="f-18">{{ __('locale.Connections') }}</span>
                            </a>
                            @endif
                        </div> --}}
                    </div>
                @elseif($tab_selected == 'contact')
                    <div class="flex-row d-flex">
                        <div class="col-auto mr-auto d-flex w-100  responsive-icons">
                            @role('Admin')
                                @can('customer_statistic')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-activity">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Statistics') }}</span>
                                </button>
                                @endcan
                            @endrole
                            @can('customer_general')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'general')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-settings">
                                        <circle cx="12" cy="12" r="3" />
                                        <path
                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.General') }}</span>
                                </button>
                            @endcan

                            @can('customer_billing')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'billing')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                        <polyline points="10 9 9 9 8 9" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Billing') }}</span>
                                </button>
                            @endcan

                            @can('customer_tariff')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'tariff')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-tag">
                                        <path
                                            d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                        <line x1="7" y1="7" x2="7.01" y2="7" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Tariffs') }}</span>
                                </button>
                            @endcan

                            @can('customer_contact')
                                <button class="btn btn-dark" wire:click="$set('tab_selected', 'contact')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-phone">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Contacts') }}</span>
                                </button>
                            @endcan

                            @role('Admin')
                                @can('customer_device')
                                    <button class="btn btn-light-info" wire:click="$set('tab_selected', 'device')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-tablet">
                                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2" />
                                            <line x1="12" y1="18" x2="12.01" y2="18" />
                                        </svg>
                                        <span class="f-18">{{ __('locale.Devices') }}</span>
                                    </button>
                                @endcan
                            @endrole
                            @if(!auth()->user()->hasRole('Admin'))
                                @can('customer_statistic')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-activity">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Statistics') }}</span>
                                </button>
                                @endcan
                            @endif
                            @role('Admin')
                                @can('customer_documents')
                                    <button class="btn btn-light-info" wire:click="$set('tab_selected', 'documents')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-file-text">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                                        </svg>
                                        <span class="f-18">{{ __('locale.Documents') }}</span>
                                    </button>
                                @endcan
                            @endrole
                        {{-- <div class="col text-right mr-2">
                            @if(!is_null($customer))
                            <a href="{{ route('customer.connections', ['customer' => $customer]) }}"
                                class="btn btn-sm btn-outline-dark" wire:key="connections-link">
                                <i class="bx bx-list-ul"></i>
                                <span class="f-18">{{ __('locale.Connections') }}</span>
                            </a>
                            @endif
                        </div> --}}
                    </div>
                    </div>
                @elseif($tab_selected == 'device')
                    <div class="flex-row d-flex">
                        <div class="col-auto mr-auto d-flex w-100  responsive-icons">
                            @role('Admin')
                                @can('customer_statistic')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-activity">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Statistics') }}</span>
                                </button>
                                @endcan
                            @endrole
                            @can('customer_general')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'general')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-settings">
                                        <circle cx="12" cy="12" r="3" />
                                        <path
                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.General') }}</span>
                                </button>
                            @endcan

                            @can('customer_billing')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'billing')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                        <polyline points="10 9 9 9 8 9" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Billing') }}</span>
                                </button>
                            @endcan

                            @can('customer_tariff')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'tariff')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-tag">
                                        <path
                                            d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                        <line x1="7" y1="7" x2="7.01" y2="7" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Tariffs') }}</span>
                                </button>
                            @endcan

                            @can('customer_contact')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'contact')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-phone">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Contacts') }}</span>
                                </button>
                            @endcan

                            @role('Admin')
                                @can('customer_device')
                                    <button class="btn btn-dark" wire:click="$set('tab_selected', 'device')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-tablet">
                                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2" />
                                            <line x1="12" y1="18" x2="12.01" y2="18" />
                                        </svg>
                                        <span class="f-18">{{ __('locale.Devices') }}</span>
                                    </button>
                                @endcan
                            @endrole
                            @if(!auth()->user()->hasRole('Admin'))
                                @can('customer_statistic')
                                    <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-activity">
                                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                        </svg>
                                        <span class="f-18">{{ __('locale.Statistics') }}</span>
                                    </button>
                                @endcan
                            @endif
                            @role('Admin')

                                @can('customer_documents')
                                    <button class="btn btn-light-info" wire:click="$set('tab_selected', 'documents')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-file-text">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                                        </svg>
                                        <span class="f-18">{{ __('locale.Documents') }}</span>
                                    </button>
                                @endcan
                            @endrole
                        </div>
                        {{-- <div class="col text-right mr-2">
                            @if(!is_null($customer))
                            <a href="{{ route('customer.connections', ['customer' => $customer]) }}"
                                class="btn btn-sm btn-outline-dark" wire:key="connections-link">
                                <i class="bx bx-list-ul"></i>
                                <span class="f-18">{{ __('locale.Connections') }}</span>
                            </a>
                            @endif
                        </div> --}}
                    </div>
                @elseif($tab_selected == 'statistic')
                    <div class="flex-row d-flex">
                        <div class="col-auto mr-auto d-flex w-100  responsive-icons">
                            @role('Admin')
                                @can('customer_statistic')
                                    <button class="btn btn-dark" wire:click="$set('tab_selected', 'statistic')"> <svg
                                            xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-activity">
                                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                        </svg>
                                        <span class="f-18">{{ __('locale.Statistics') }}</span>
                                    </button>
                                @endcan
                            @endrole
                            @can('customer_general')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'general')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-settings">
                                        <circle cx="12" cy="12" r="3" />
                                        <path
                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.General') }}</span>
                                </button>
                            @endcan

                            @can('customer_billing')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'billing')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                        <polyline points="10 9 9 9 8 9" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Billing') }}</span>
                                </button>
                            @endcan

                            @can('customer_tariff')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'tariff')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-tag">
                                        <path
                                            d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                        <line x1="7" y1="7" x2="7.01" y2="7" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Tariffs') }}</span>
                                </button>
                            @endcan

                            @can('customer_contact')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'contact')">
                                    <svg  xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-phone">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Contacts') }}</span>
                                </button>
                            @endcan

                            @role('Admin')
                                @can('customer_device')
                                    <button class="btn btn-light-info" wire:click="$set('tab_selected', 'device')"> <svg
                                            xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-tablet">
                                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2" />
                                            <line x1="12" y1="18" x2="12.01" y2="18" />
                                        </svg>
                                        <span class="f-18">{{ __('locale.Devices') }}</span>
                                    </button>
                                @endcan
                            @endrole

                            @if(!auth()->user()->hasRole('Admin'))
                                @can('customer_statistic')
                                    <button class="btn btn-dark" wire:click="$set('tab_selected', 'statistic')"> <svg
                                            xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-activity">
                                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                        </svg>
                                        <span class="f-18">{{ __('locale.Statistics') }}</span>
                                    </button>
                                @endcan
                            @endif
                            @role('Admin')
                                @can('customer_documents')
                                    <button class="btn btn-light-info" wire:click="$set('tab_selected', 'documents')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-file-text">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                                        </svg>
                                        <span class="f-18">{{ __('locale.Documents') }}</span>
                                    </button>
                                @endcan
                            @endrole


                        </div>
                        {{-- <div class="col text-right mr-2">
                            @if(!is_null($customer))
                            <a href="{{ route('customer.connections', ['customer' => $customer]) }}"
                                class="btn btn-sm btn-outline-dark" wire:key="connections-link">
                                <i class="bx bx-list-ul"></i>
                                <span class="f-18">{{ __('locale.Connections') }}</span>
                            </a>
                            @endif
                        </div> --}}
                    </div>
                @elseif($tab_selected == 'documents')
                    <div class="flex-row d-flex">
                        <div class="col-auto mr-auto d-flex w-100  responsive-icons">
                            @role('Admin')
                                @can('customer_statistic')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-activity">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Statistics') }}</span>
                                </button>
                                @endcan
                            @endrole
                            @can('customer_general')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'documents')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-settings">
                                        <circle cx="12" cy="12" r="3" />
                                        <path
                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.General') }}</span>
                                </button>
                            @endcan

                            @can('customer_billing')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'billing')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                        <polyline points="10 9 9 9 8 9" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Billing') }}</span>
                                </button>
                            @endcan

                            @can('customer_tariff')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'tariff')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-tag">
                                        <path
                                            d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                        <line x1="7" y1="7" x2="7.01" y2="7" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Tariffs') }}</span>
                                </button>
                            @endcan

                            @can('customer_contact')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'contact')">
                                    <svg  xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-phone">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Contacts') }}</span>
                                </button>
                            @endcan

                            @role('Admin')
                                @can('customer_device')
                                    <button class="btn btn-light-info" wire:click="$set('tab_selected', 'device')"> <svg
                                            xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-tablet">
                                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2" />
                                            <line x1="12" y1="18" x2="12.01" y2="18" />
                                        </svg>
                                        <span class="f-18">{{ __('locale.Devices') }}</span>
                                    </button>
                                @endcan
                            @endrole
                            @if(!auth()->user()->hasRole('Admin'))
                                @can('customer_statistic')
                                <button class="btn btn-light-info" wire:click="$set('tab_selected', 'statistic')"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-activity">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                    </svg>
                                    <span class="f-18">{{ __('locale.Statistics') }}</span>
                                </button>
                                @endcan
                            @endif
                            @role('Admin')
                                @can('customer_documents')
                                <button class="btn btn-dark" wire:click="$set('tab_selected', 'documents')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                                    </svg>
                                    <span class="f-18">{{ __('locale.Documents') }}</span>
                                </button>
                                @endcan
                            @endrole
                        </div>
                        {{-- <div class="col text-right mr-2">
                            @if(!is_null($customer))
                            <a href="{{ route('customer.connections', ['customer' => $customer]) }}"
                                class="btn btn-sm btn-outline-dark" wire:key="connections-link">
                                <i class="bx bx-list-ul"></i>
                                <span class="f-18">{{ __('locale.Connections') }}</span>
                            </a>
                            @endif
                        </div> --}}
                    </div>
                @endif
            </div>

            {{--TAB GENERAL--}}
            @if($tab_selected == 'general' && auth()->user()->can('customer_general'))
                <div class="row">
                    <div class="col-lg-6 mt-1">
                        <div class="mt-4">
                            <div class="width-60-per float-left pr-1">
                                <label for="customer_name">{{ __('locale.Customer Name') }}</label>
                                <input id="customer_name" type="text" class="form-control flex-row"
                                    wire:model="customer_name"> {{--placeholder="{{ __('locale.Company Name') }}"--}}
                                @error('customer_name') <span class="error" style="color: #ff0000">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="width-40-per float-left mt-2">
                                <label for="planned_operating_time">{{ __('locale.Planned Operating Time') }}</label>
                                <input id="planned_operating_time" type="text" data-type="time"
                                    class="form-control flex-row" wire:model="planned_operating_time" readonly placeholder="00:00"> {{--placeholder="{{
                                __('locale.Planned Operating Time') }}"--}}
                                @error('planned_operating_time') <span class="error" style="color: #ff0000">{{ $message
                                    }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-1">
                        <div class="mt-4">
                            <label for="address">{{ __('locale.Address') }}</label>
                            <input id="address" type="text" class="form-control flex-row" wire:model="address">
                            {{--placeholder="{{ __('locale.Address') }}"--}}
                            @error('address') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 mt-1">
                        <div class="row">
                            <div class="col-lg-3 mt-1">
                                <label for="postcode">{{ __('locale.ZIP') }}</label>
                                <input id="post_code" type="text" id="postcode" class="form-control flex-row"
                                    wire:model="post_code"> {{--placeholder="{{ __('locale.ZIP') }}"--}}
                                @error('post_code') <span class="error" style="color: #ff0000">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-9 mt-1">
                                <label for="cities">{{ __('locale.City') }}</label>
                                <input id="city" type="text" class="form-control flex-row" wire:model="city">
                                {{--placeholder="{{ __('locale.City') }}"--}}
                                @error('city') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label for="country">{{ __('locale.Country') }}</label>
                        <input id="country" type="text" class="form-control flex-row" wire:model="country">
                        {{--placeholder="{{ __('locale.Country') }}"--}}
                        @error('country') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label for="email">{{ __('locale.Email') }}</label>
                        <input type="email" id="email" class="form-control flex-row" wire:model="email"> {{--placeholder="{{
                        __('locale.Email') }}"--}}
                        @error('email') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label for="phone">{{ __('locale.Phone').' ' }} <small class="text-muted"></small></label>
                        <input type="text" id="phone" class="form-control flex-row" wire:model="phone"> {{--placeholder="{{
                        __('locale.Phone number') }}"--}}
                        @error('phone') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label for="comment">{{ __('locale.Comments') }} / {{ __('locale.Info') }}</label>
                        <textarea type="text" id="comment" class="form-control flex-row" style="height: 150px;"
                            wire:model="comment"></textarea>
                        @error('comment') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label for="website">{{ __('locale.Website') }}</label>
                        <input type="text" id="website" class="form-control flex-row" wire:model="website" placeholder="">
                        {{----}}
                        @error('website') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror

                        @if (APP_EDITION == 'Bdgo')
                            <label for="customer_type_id">{{ __('locale.Customer Type') }}</label>
                            <select class="form-control" wire:model="customer_type_id" id="customer_type_id">
                                @if (!empty($customerTypes) && !$customerTypes->isEmpty())
                                    @foreach ($customerTypes as $customerType)
                                        <option value="{{ $customerType->id }}">
                                            @switch ($customerType->type)
                                                @case("0")
                                                    {{ __('locale.All') }}
                                                    @break
                                                @case("1")
                                                    {{ __('locale.Church') }}
                                                    @break
                                                @case("2")
                                                    {{ __('locale.SME') }}
                                                    @break
                                                @case("3")
                                                    {{ __('locale.School') }}
                                                    @break
                                                @case("4")
                                                    {{ __('locale.Authorities') }}
                                                    @break
                                                @case("5")
                                                    {{ __('locale.Association') }}
                                                    @break
                                                @case("6")
                                                    {{ __('locale.Health Care') }}
                                                    @break
                                                @case("7")
                                                    {{ __('locale.Medical Professions') }}
                                                    @break
                                            @endswitch
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                            @error('customer_type_id') <span class="error" style="color: #ff0000;">{{ $message }}</span> @enderror
                        @endif
                    </div>
                </div>

            {{--TAB BILLING--}}
            @elseif($tab_selected == 'billing' && auth()->user()->can('customer_billing'))
                <div class="row">
                    <div class="col-lg-6 mt-4">
                        <h4>{{ __('locale.Invoice Recipient') }}</h4>
                        <div class="mt-1">
                            <label for="billing_addition">{{ __('locale.Address Addition') }}</label>
                            <input id="billing_addition" type="text" name="billing_addition" class="form-control"
                                wire:model="billing_addition"> {{--placeholder="{{ __('locale.Address Addition') }}"--}}
                            @error('billing_addition') <span class="error" style="color: #ff0000">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <label for="billing_address">{{ __('locale.Invoice Address') }}</label>
                            <input type="text" id="billing_address" class="form-control flex-row"
                                wire:model="billing_address"> {{-- placeholder="{{ __('locale.Invoice Address') }}"--}}
                            @error('billing_address') <span class="error" style="color: #ff0000">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-lg-3 mt-2">
                                <label for="billing_zip_code">{{ __('locale.ZIP') }}</label>
                                <input type="text" id="billing_zip_code" class="form-control" wire:model="billing_zip_code">
                                {{-- placeholder="{{ __('locale.ZIP') }}" --}}
                                @error('billing_zip_code') <span class="error" style="color: #ff0000">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-9 mt-2">
                                <label for="billing_city">{{ __('locale.City') }}</label>
                                <input type="text" id="billing_city" class="form-control" wire:model="billing_city"> {{--
                                placeholder="{{ __('locale.City') }}" --}}
                                @error('billing_city') <span class="error" style="color: #ff0000">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-2">
                            <label for="billing_country">{{ __('locale.Country') }}</label>
                            <input type="text" id="billing_country" class="form-control flex-row"
                                wire:model="billing_country"> {{-- placeholder="{{ __('locale.Country') }}" --}}
                            @error('billing_country') <span class="error" style="color: #ff0000">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 mt-4">
                        <h4>{{ __('locale.Invoice') }}</h4>
                        <div class="mt-1 mb-2 width-50-per float-left pr-1">
                            <label for="billing_iban">IBAN</label>
                            <input id="billing_iban" type="text" name="billing_iban" class="form-control"
                                wire:model="billing_iban"> {{-- placeholder="IBAN" --}}
                            @error('billing_iban') <span class="error" style="color: #ff0000">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-2 mb-2 width-50-per float-left">
                            <label for="billing_bic">BIC</label>
                            <input id="billing_bic" type="text" name="billing_bic" class="form-control"
                                wire:model="billing_bic"> {{-- placeholder="BIC" --}}
                            @error('billing_bic') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-1">
                            <label for="billing_email">{{ __('locale.Email') }} {{ __('locale.Accounting') }}</label>
                            <input id="billing_email" type="email" name="billing_email" class="form-control"
                                wire:model="billing_email"> {{-- placeholder="{{ __('locale.Email') }} {{
                            __('locale.Accounting') }}" --}}
                            @error('billing_email') <span class="error" style="color: #ff0000">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <label for="billing_payment">{{ __('locale.Payment Term') }} <small>({{ __('locale.Days')
                                    }})</small></label>
                            <input id="billing_payment" type="text" name="billing_payment" class="form-control"
                                wire:model="billing_payment"> {{-- placeholder="{{ __('locale.Payment Term') }}" --}}
                            @error('billing_payment') <span class="error" style="color: #ff0000">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <label for="billing_sepa">SEPA</label>
                            <select id="billing_sepa" name="billing_sepa" class="form-control" wire:model="billing_sepa">
                                <option value="0" selected>False</option>
                                <option value="1">True</option>
                            </select>
                            @error('billing_sepa') <span class="error" style="color: #ff0000">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

            {{--TAB TARIFF--}}
            @elseif($tab_selected == 'tariff' && auth()->user()->can('customer_tariff'))
                <div>
                    <div class="mt-4">
                        @livewire('tenant.tariff-component',['customer' => $customer, 'customer_component' => true])
                    </div>
                </div>

            {{--TAB CONTACT--}}
            @elseif($tab_selected == 'contact' && auth()->user()->can('customer_contact'))
                <div>
                    <div class="mt-4">
                        @livewire('tenant.contact-component',['customer' => $customer], key($customer ?
                        'contact-'.$customer->id : 'contact-123456'))
                    </div>
                </div>

            {{--TAB DEVICES--}}
            @elseif($tab_selected == 'device' && auth()->user()->can('customer_device'))
                <div>
                    <div class="mt-4">
                        @livewire('tenant.device-component',['customer' => $customer], key($customer ?
                        'device-'.$customer->id : 'device-123456'))
                    </div>
                </div>

            {{--TAB STATISTICS--}}
            @elseif($tab_selected == 'statistic' && auth()->user()->can('customer_statistic'))
                <div class="row">
                    <!-- Overview operating times Starts -->
                    <div class="col-md-8 col-12 order-summary border-right pr-md-0">
                        <div class="mb-0">
                            <div class="card-header d-flex justify-content-between align-items-center"
                                style="padding-right: 0;">
                                <h4 class="card-title">{{__('locale.Overview operating times')}}</h4>
                                <div class="d-flex">
                                    <button type="button"
                                        class="btn btn-sm {{$duration_months == 1 ? 'btn-dark glow' : 'btn-light-primary'}} mr-1"
                                        wire:click="$set('duration_months', 1)">{{__('locale.Month')}}</button>
                                    <button type="button"
                                        class="btn btn-sm {{$duration_months == 12 ? 'btn-dark glow' : 'btn-light-primary'}}"
                                        wire:click="$set('duration_months', 12)">{{__('locale.Quarter')}}</button>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body p-0">
                                    <div id="overview-operating-times-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sales History Starts -->
                    <div class="col-md-4 col-12 pl-md-0">
                        <div class="mb-0">
                            <div class="card-header pb-50 d-flex flex-row justify-content-between">
                                <div>
                                    <h4 class="card-title" style="margin-bottom: 0;">{{__('locale.Comparison')}}</h4>

                                    <h6 class="text-right" style="font-size: 10px;">
                                        @if ($duration_months == '12')
                                            @if ($this->year == self::DEFAULT_YEAR)
                                                <i>{{ __('locale.Annual Quarterly Report') }}</i>
                                            @else
                                                <i>{{ __('locale.Yearly Report') }}</i>
                                            @endif
                                        @elseif ($duration_months == '1')
                                            @if ($this->quarter == self::DEFAULT_QUARTER)
                                                <i>{{ __('locale.Monthly Report') }}</i>
                                            @else
                                                <i>{{ __('locale.Quarterly Report') }}</i>
                                            @endif
                                        @endif
                                    </h6>
                                </div>

                                <div>
                                    @if (!empty($quarters))
                                        @if ($duration_months == 1)
                                            <select class="form-control" style="width: auto;" wire:model="quarter">
                                                <option value="1" selected="true">{{ __('locale.All') }}</option>
                                                @foreach ($quarters as $key => $quarter)
                                                    <option value="{{ $key }}">Q{{ $key }}</option>
                                                @endforeach
                                            </select>
                                    @elseif ($duration_months == 12)
                                            <select class="form-control" style="width: auto;" wire:model="year">
                                                <option value="1" selected="true">{{ __('locale.All') }}</option>
                                                @foreach ($years as $key => $year)
                                                    <option value="{{ $key }}">{{ $key }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="card-content">
                                <div class="card-body py-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="sales-item-name">
                                            <p class="mb-0">{{__('locale.Planned Operating Time')}}</p>

                                            <h6 class="text-right" style="font-size: 10px;">
                                                @if ($duration_months == self::DEFAULT_DURATION_MONTH)
                                                    <i>{{ __("locale.Average") }}</i>
                                                @else
                                                    @if ($this->year != self::DEFAULT_YEAR)
                                                        <i>{{ __("locale.Average") }}</i>
                                                    @else
                                                        <i>{{ __("locale.Sum") }}</i>
                                                    @endif
                                                @endif
                                            </h6>
                                        </div>
                                        <div class="sales-item-amount">
                                            <h6 class="mb-0">
                                                {{ \App\Helpers\Helper::formatHoursAndMinutes($series_data['total_planned_operating_time']) }}
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="sales-item-name">
                                            <p class="mb-0">{{__('locale.Actual Operating Time')}}</p>

                                            <h6 class="text-right" style="font-size: 10px;">
                                                @if ($series_data['is_average'])
                                                    <i>{{ __("locale.Average") }}</i>
                                                @else
                                                    <i>{{ __("locale.Sum") }}</i>
                                                @endif
                                            </h6>
                                        </div>
                                        <div class="sales-item-amount">
                                            <h6 class="mb-0">
                                                {{!empty($series_data['total_actual_operating_time']) ?
                                                \App\Helpers\Helper::formatHoursAndMinutes($series_data['total_actual_operating_time'],'%02d:%02dh')
                                                : 0}}
                                            </h6>

                                            <h6 style="font-size: 10px;">&nbsp;</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer border-top pb-0">
                                    <h5>{{__('locale.Difference')}}</h5>
                                    <span
                                        class="{{$series_data['total_operating_time_duration'] > 0 ? 'text-danger' : 'text-success'}} text-bold-500">
                                        {{ $series_data['total_operating_time_duration'] < 0 ? '-' : '+' }} {{
                                            \App\Helpers\Helper::formatHoursAndMinutes(abs($series_data['total_operating_time_duration']), '%02d:%02dh'
                                            ) }} </span>

                                            @if ($series_data['total_operating_time_duration'] > 0)
                                            <span>
                                                {{ __('locale.above / below planned operating time') }}
                                            </span>
                                            @endif

                                            <div class="progress progress-bar-primary progress-sm my-50">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="78"
                                                    style="width:78%"></div>
                                            </div>

                                            <br />

                                            @if ($series_data['total_operating_time_duration'] > 0)
                                            <div>
                                                <h6>{{ __('locale.Recommendation') }} :</h6>
                                                {{ __('locale.Time DIfference Recommendation') }}
                                            </div>
                                            @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{--TAB DOCUMENTS--}}
            @elseif($tab_selected == 'documents' && auth()->user()->can('customer_documents'))
                <div>
                    <div class="mt-4">
                        @livewire('tenant.document-component',['customer' => $customer, 'customer_component' => true])
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="card-footer">
        @if($tab_selected != 'statistic' && $tab_selected != 'documents')
        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
            @if(auth()->user()->hasRole('Admin'))
            @if(is_null($customer))
            <button style="margin-right: 5px;" class="btn btn-outline-dark glow mb-sm-0 mr-sm-1 my-1"
                wire:click="save('new')" wire:loading.attr="disabled">
                <span>{{ __('locale.Save') }} & {{ __('locale.New') }}</span>
                <span style="margin-left: 5px;" wire:loading wire:target="save('new')">
                    <div style="color: #F2F2F2;" class="la-line-scale la-sm">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </span>
            </button>
            @endif
            <button style="margin-right: 5px;" class="btn btn-dark glow mb-sm-0 mr-sm-1 my-1" wire:click="save('close')"
                wire:loading.attr="disabled">
                <span>{{ __('locale.Save') }}</span>
                <span style="margin-left: 5px;" wire:loading wire:target="save('close')">
                    <div style="color: #F2F2F2;" class="la-line-scale la-sm">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </span>
            </button>
            @endif
            <button class="btn btn-outline-dark glow mb-1 mb-sm-0 mr-0 mr-sm-1 p-1 px-2 my-1 inline-block"
                wire:click="cancel">{{ __('locale.Cancel') }}</button>
        </div>
        @endif
    </div>
</div>

{{-- vendor scripts --}}
{{-- @section('vendor-scripts')
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection --}}

{{-- page scrips --}}
{{-- @section('page-scripts')
<script src="{{asset('js/scripts/forms/select/form-select2.js')}}"></script>
<script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
@endsection --}}

{{-- custom scripts --}}
@section('custom_scripts')
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>--}}
<script src="{{ mix('assets/vendor/libs/jquery-mask-plugin/dist/jquery.mask.min.js') }}"></script>
<script src="{{ mix('assets/vendor/libs/select2/select2.js') }}"></script>
<script>
    $(window).on('beforeunload', function() {
            $(window).scrollTop(0);
        });
        $(document).ready(function() {
            Livewire.hook('element.updated', (el, component) => {
                const url = new URL(window.location);

                url.searchParams.set('tab_selected', @this.tab_selected);

                if (@this.tab_selected == 'statistic') {
                    url.searchParams.set('duration_months', @this.duration_months);
                    url.searchParams.set('quarter', @this.quarter);
                    url.searchParams.set('year', @this.year);
                }

                window.history.pushState(null, '', url.toString());
            });

            let $locale_sys = "{!! config('app.locale') !!}";
            window.loadContactDeviceSelect2 = () => {
                $('.contact_devices_multiple').select2({
                    "language": {
                        "noResults": function () {
                            if ($locale_sys === 'de')
                                return "keine Ergebnisse gefunden";
                            else
                                return "No results found";
                        }
                    }
                }).on('change',function () {
                    livewire.emitTo('tenant.contact-component','devicesSelect',$(this).val());
                });
            }
            loadContactDeviceSelect2();
            window.livewire.on('loadContactDeviceSelect2',()=>{
                loadContactDeviceSelect2();
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
                hideMethod: 'fadeOut'
            };

            // setup for statistics when redirecting from dashboard
            var show_statistics = "{{ (empty(request()->get('gs', '')) && url()->previous() == route('dashboard')) }}";
            if (show_statistics) {
                @this.set('tab_selected', 'statistic');
            }

            let flatpickrTime = $("#planned_operating_time");

            if (flatpickrTime && flatpickrTime.length > 0) {
                let $locale = "{!! config('app.locale') !!}";

                if ($locale === "en") {
                    flatpickr(flatpickrTime, {
                        enableTime: true,
                        noCalendar: true,
                        time_24hr: true,
                        dateFormat: "H:i",
                        disableMobile: "true",
                        defaultDate: @this.planned_operating_time
                    });
                } else {
                    flatpickr(flatpickrTime, {
                        enableTime: true,
                        noCalendar: true,
                        time_24hr: true,
                        disableMobile: "true",
                        dateFormat: "H:i",
                        "locale": "de",
                        defaultDate: @this.planned_operating_time
                    });
                }
            }
        });


        // Overview Operating Times Chart
        // --------------------

        function renderOverviewOperatingTimesChart(seriesData)
        {
            if (isDarkStyle) {
                var axisColor = config.colors_dark.axisColor;
            } else {
                var axisColor = config.colors.axisColor;
            }

            var overviewOperatingTimesChartOptions = {
                chart: {
                    id: 'mychart',
                    height: 350,
                    type: 'line',
                    stacked: false,
                    toolbar: {
                        show: true,
                    },
                    sparkline: {
                        enabled: false
                    },
                },
                zoom: {
                    enabled: true,
                    type: 'x',
                    autoScaleYaxis: false
                },
                colors: ['#5A8DEE', '#FDAC41'],
                dataLabels: {
                    enabled: false
                },
                /*fill: {
                    type: 'gradient',
                    gradient: {
                        inverseColors: false,
                        shade: 'light',
                        type: "vertical",
                        gradientToColors: ['#E2ECFF', '#5A8DEE'],
                        opacityFrom: 0.7,
                        opacityTo: 0.55,
                        stops: [0, 80, 100]
                    }
                },*/
                legend: {
                    labels: {
                        colors: axisColor
                    },
                },
                series: [{
                    name: "{{__('locale.Actual Operating Time')}}",
                    data: seriesData.actual_operating_time_data,
                    type: 'line',
                }, {
                    name: "{{__('locale.Planned Operating Time')}}",
                    data: seriesData.planned_operating_time_data,
                    type: 'line',
                }],
                stroke: {
                    curve: 'smooth',
                    width: 2.5,
                    dashArray: [0, 8]
                },
                grid: {
                    padding: {
                        left: 30
                    }
                },
                xaxis: {
                    categories: seriesData.x_axis_data,
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    labels: {
                        show: true,
                        style: {
                            colors: axisColor
                        }
                    }
                },
                yaxis: {
                    show: true,
                    showAlways: true,
                    decimalsInFloat: 2,
                    labels: {
                        show: true,
                        style: {
                            colors: axisColor
                        }
                    }
                },
                tooltip: {
                    x: {
                        formatter: function (value, {series, seriesIndex, dataPointIndex, w}) {
                            if (seriesData.x_axis_data[dataPointIndex] == 0) {
                                return 0;
                            }
                            return (seriesData.duration === 'monthly')
                                ? '{{__('locale.Month')}}: ' + seriesData.x_axis_data[dataPointIndex]
                                : '{{__('locale.Period')}}: ' + seriesData.x_axis_data[dataPointIndex];
                        }
                    },
                    y: {
                        formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
                            let time    = series[seriesIndex][dataPointIndex];
                            let times   = String(time).split(".");
                            let hours   = (times[0]) ? times[0] : '00';
                            let minutes = (times[1]) ? times[1] : '00';

                            // Pad zero for single character.
                            if (hours.length == 1) {
                                hours = '0' + hours;
                            }
                            if (minutes.length == 1) {
                                minutes = minutes + '0';
                            }

                            // Show two chars for minutes.
                            if (minutes.length > 2) {
                                minutes = minutes.substring(0, 2);
                            }

                            return hours + ":" + minutes + "h";
                        }
                    }
                },
            };

            document.getElementById("overview-operating-times-chart").innerHTML = "";

            const chartElement = document.createElement('div');

            chartElement.setAttribute('id', 'overview-operating-times-chart-inner');

            document.getElementById("overview-operating-times-chart").appendChild(chartElement);

            var overviewOperatingTimesChart = new ApexCharts(
                document.getElementById("overview-operating-times-chart-inner"),
                overviewOperatingTimesChartOptions
            );

            overviewOperatingTimesChart.render();
        }

        window.addEventListener('renderOverviewOperatingTimesChart', event => {
            renderOverviewOperatingTimesChart(@this.series_data);
        });

        // Default when page load if statistic tab selected.
        let tabSelected = '{{ $tab_selected }}';

        if (tabSelected == 'statistic') {
            let seriesDataJson = '{!! json_encode($series_data) !!}',
                seriesData     = JSON.parse(seriesDataJson);

            renderOverviewOperatingTimesChart(seriesData);
        }

        window.addEventListener('focusInput', event => {
            $(window).scrollTop(0);
        })
        window.addEventListener('focusErrorInput', event => {
            var $field = '#' + event.detail.field;
            $($field).focus()
        })
        window.addEventListener('showToastrSuccess', event => {
            toastr.success('', event.detail.message).css("width","fit-content")
        })
        window.addEventListener('showToastrDelete', event => {
            toastr.warning('', event.detail.message).css("width","fit-content")
        })
        window.addEventListener('showToastrError', event => {
            toastr.error('',event.detail.message).css("width","fit-content")
        })

        window.addEventListener('initHourMinutePicker', event => {
            initHourMinutePicker();
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
        window.addEventListener('toggleContactDiv', event => {
            let div = $('#collapseDivForContact');
            if (div.hasClass('show')) {
                livewire.emitTo('tenant.contact-component','cleanVars');
                div.collapse("hide");
            } else {
                div.collapse("show");
            }
        });

        window.addEventListener('toggleDeviceDiv', event => {
            let div = $('#collapseDivForDevice');
            if (div.hasClass('show')) {
                livewire.emitTo('tenant.device-component','cleanVars');
                div.collapse("hide");
            } else {
                div.collapse("show");
            }
        });

        window.addEventListener('toggleDocumentDiv', event => {
            let div = $('#collapseDivForDocument');
            if (div.hasClass('show')) {
                livewire.emitTo('tenant.document-component','cleanVars');
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

        window.addEventListener('openToggleContactDiv', event => {
            $('#collapseDivForContact').collapse("show");
        })
        window.addEventListener('closeToggleContactDiv', event => {
            $('#collapseDivForContact').collapse("hide");
        })

        window.addEventListener('openToggleDeviceDiv', event => {
            $('#collapseDivForDevice').collapse("show");
            window.scrollTo(0, 0);
        })
        window.addEventListener('closeToggleDeviceDiv', event => {
            $('#collapseDivForDevice').collapse("hide");
        })

        window.addEventListener('openTariffModalDelete', event => {
            $("#tariffModalDelete").modal('show');
        })

        window.addEventListener('closeTariffModalDelete', event => {
            $("#tariffModalDelete").modal('hide');
        })

        window.addEventListener('openContactModalDelete', event => {
            $("#contactModalDelete").modal('show');
        })

        window.addEventListener('closeContactModalDelete', event => {
            $("#contactModalDelete").modal('hide');
        })

        window.addEventListener('openDeviceModalDelete', event => {
            $("#deviceModalDelete").modal('show');
        })

        window.addEventListener('closeDeviceModalDelete', event => {
            $("#deviceModalDelete").modal('hide');
        })

        window.addEventListener('openTariffModalForceDelete', event => {
            $("#tariffModalForceDelete").modal('show');
        })

        window.addEventListener('closeTariffModalForceDelete', event => {
            $("#tariffModalForceDelete").modal('hide');
        })

        window.addEventListener('openActivateTariffModal', event => {
            $("#tariffModalActivate").modal('show');
        })

        window.addEventListener('closeActivateTariffModal', event => {
            $("#tariffModalActivate").modal('hide');
        })

        window.addEventListener('openDocumentModalDelete', event => {
            $("#DocumentModalDelete").modal('show');
        })

        window.addEventListener('closeDocumentModalDelete', event => {
            $("#DocumentModalDelete").modal('hide');
        })

        window.initPlannedOperatingTimeMasks = () => {
            var maskBehavior = function (val) {
                return "HZSHZ";
            }
            spOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(maskBehavior.apply({}, arguments), options);
                },
                translation: {
                    'H': { pattern: /\d/, optional: false },
                    'Z': { pattern: /[0-9]/, optional: true },
                    'S': { pattern: /[,-.-:]/, optional: false },
                }
            };
            $("input[data-type='time']").mask(maskBehavior, spOptions);
        };
        initPlannedOperatingTimeMasks();

        window.initHourMinutePicker = () => {

        };



        initHourMinutePicker();

</script>
@endsection
