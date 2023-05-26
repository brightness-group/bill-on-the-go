@extends('tenant.theme-new.layouts.layoutMaster')
@section('title', __('locale.Connections'))

@section('vendor-style')
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/css/connections_component.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/connection_edit_component_css.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/loading_states_awesome.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/pages/activity-overview.css')}}">

@endsection


@section('custom_styles')

{{-- new styles --}}
<style>
    .light-style .customer-custom-dropdown .select2-container--default .select2-selection--single,
    .dark-style .customer-custom-dropdown .select2-container--default .select2-selection--single {
        height: 30px;
    }

    .light-style .customer-custom-dropdown .select2-container--default .select2-selection--single .select2-selection__rendered,
    .dark-style .customer-custom-dropdown .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.7rem;
    }

    .light-style .customer-custom-dropdown .select2-container--default .select2-selection--single .select2-selection__arrow,
    .dark-style .customer-custom-dropdown .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 1.75rem;
    }

    .width-align.customer-custom-dropdown{
        min-width:14.7%;
    }
    #select2-customers-container,.customer-custom-dropdown .select2-results__option{
        font-size: 0.813rem;
    }

    .generatePDF.disabled {
        color: #69809a;
        background-color: transparent;
        cursor: default;
        pointer-events: auto;
        opacity: 0.65;
    }
    .master-checkbox {
        min-width: 18px;
        min-height: 18px;
    }

    .border-line-emergency {
        background-color: hsl(64, 76%, 85%);
    }

    .overlaps-user {
        background-color: hsl(356, 76%, 95%);
    }

    .connect-type {
        width: 20px;
        height: 20px;
    }

    element.style {}

    .table:not(.table-dark) thead:not(.table-dark) th {
        color: #516377;
    }

    .table th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        font-weight: bold;
    }

    .table> :not(caption)>*>* {
        padding: 0.325rem 1.5rem;
    }

    .width-align {
        min-width: 8.7%;
        width: 100%
    }

    .dw {
        min-width: 150px;
        height: 30px;
    }

    .width-align .form-select {
        height: 30px;
    }

    .width-align label {
        font-weight: 700;
    }

    .t-space {
        top: 19px;
        position: relative;
    }

    .dw .bx {
        font-size: 23px;
    }

    .small-icon-w {
        min-width: 42px;
    }

    .table-ao {
        height: calc(100vh - 353px);
    }

    .table-ao thead {
        position: sticky;
        top: 0;
    }

    .table-ao thead {
        background: #ffffff;
    }
    .dark-style .table-ao thead {
        background: #283144;
    }
    .table:not(.table-dark) thead:not(.table-dark) th {
        color: var(--bs-body-color);
    }

    .flatpickr-prev-month,
    .flatpickr-next-month {
        display: flex;
        align-items: center;
        border-radius: 50%;
        margin: 5px;
    }

    .pagination {
        justify-content: end;
    }

    .h-40 {
        height: 40px !important;
    }

    .h-30 {
        height: 30px !important;
    }

    .fs-12 {
        font-size: 12px !important;
    }

    .mr-15 {
        margin-right: 15px;
    }

    .d-inline-flex {
        display: inline-flex !important;
    }

    .top-space {
        position: relative;
        top: 4px;
        margin-right: 10px
    }

    /* Compact listing. */
    .customer-connections-common {
        font-size: 10px !important;
    }

    .customer-connections-common td,
    .customer-connections-common th {
        padding: 0.325rem 0rem;
        /* position: relative; */
    }

    .f-10px {
        font-size: 10px !important;
    }

    .pl-0,
    .px-0 {
        padding-left: 0 !important;
    }

    .pr-0,
    .px-0 {
        padding-right: 0 !important;
    }

    .job-description {
        width: 10%;
        white-space: normal !important;
    }

    .action-dots {
        display: block;
        width: 30px;
        height: 30px;
        left: 39px;
        margin-top: 9px;
    }

    .width-align .form-label {
        font-size: 10px;
        height: 30px;
    }

    .width-align .input-group-text {
        height: 30px;
    }

    .width-align .input-group .form-control {
        height: 30px;
    }

    .small-icon-w .btn-icon {
        top: 7px;
        position: relative;
        left: -10px;
    }

    #row_checkbox_connection_main {
        margin-top: -6px;
    }
    .flatpickr-months .flatpickr-prev-month svg, .flatpickr-months .flatpickr-next-month svg{
        height: 0.7rem;
        width: 0.7rem;
    }
    .flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month{
        height: 26px;
        padding: 7px;
    }
    .flatpickr-day{
        line-height: 31px;
    }
    .flatpickr-current-month input.cur-year{
        font-size: 13px;
        font-weight: 400;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months{
        font-size: 13px;
        font-weight: 400;
    }
    .flatpickr-current-month .numInputWrapper {
        width: 9ch;
    }
    .numInputWrapper span{
        width: 15px;
    }

    .total_duration,
    .sorting_desc-duration_with_interval,
    .sorting_asc-duration_with_interval,
    .with-tarif-interval {
        color: #FDAC41 !important;
        font-weight: bold;
    }

    .total_duration, .without_duration, .total_amount {
        margin-left: 5px;
        pointer-events: none;
    }

    .total_duration:hover, .without_duration:hover, .total_pot:hover, .total_amount:hover {
        background-color: unset;
        color: unset;
    }

    #potInfoModel .modal-body {
        text-align: left;
    }

    @media only screen and (max-width: 1350px) {
        .dw {
            min-width: 130px;
            height: 30px;
        }

        .small-icon-w .btn-icon {
            top: 8px;
            position: relative;
            left: -27px;
        }
    }
</style>
@endsection

@section('content')
<div class="row" id="activity-overview-connections">
    <!-- New Page start -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {{-- header first section --}}
                    <div class="col-md-3 col-12 ">
                        {{-- page title --}}
                        <div class="d-inline-flex">
                            <h3>{{ __('locale.Activity Overview') }}</h3>
                        </div>

                        {{-- selected customer name --}}
                        <br />
                        <div class="d-inline-flex" id="customer_name_title" style="visibility: hidden;height: 23px;">
                            <h5>
                                <strong></strong>
                            </h5>
                            &nbsp;
                            <a id="customer_edit_link" target="__blank" class="nav-link" style="padding: unset;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                    <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-9  col-12  text-right text-sm-left">
                        {{-- print switch --}}
                        {{-- todo:: temporary commented for testing --}}
                        {{--<div class="d-inline-flex">
                            @role('Admin')
                            <label class="switch switch-sm me-0" @if(!isset($customer))
                                title="{{ __('locale.Please select a customer from your list first') }}" @endif>
                                <input type="checkbox" wire:model="printView" class="switch-input"
                                    id="customSwitchPrintView" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label">{{ __('locale.Print View') }}</span>
                            </label>

                            <div wire:loading.flex wire:target="printView" style="position: absolute;">
                                <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm"
                                    style="margin-left: 60px;color: #495057;"></x-loading.ball-spin-clockwise>
                            </div>
                            @endrole
                        </div>--}}

                        @if(empty($counter))
                        @php
                        // todo: update based on print view.
                        $printView = false;
                        @endphp
                        @endif

                        {{-- print pdf button --}}
                        @role('Admin')
                            {{-- edit customer button --}}
                            {{-- <button disabled class="btn btn-icon btn-xs btn-outline-dark edit-customer-btn h-30">
                                <span class="bx bxs-map"></span>
                            </button> --}}

                            {{-- Search input --}}
                            <div class="d-none">
                                <input {{ $printView ? 'disabled' : '' }} id="search_term"
                                    title="@if($printView) {{ __('locale.Please close the print view first') }} @endif"
                                    type="search" value="{{ request()->get('gs', '') }}"
                                    style="width: 135px;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 12px;color: #7DA0B1;margin-right: 3px;"
                                    class="d-inline form-control form-control-sm h-30"
                                    placeholder="{{ __('locale.Search...') }}">
                            </div>

                            {{-- Search input --}}
                            {{-- <div class="d-inline">
                                <div class="input-group input-group-merge w-25">
                                    <span class="input-group-text" id="basic-addon-search31"><i
                                            class="bx bx-search"></i></span>
                                    <input type="text" class="form-control form-control-sm" placeholder="Search..."
                                        aria-label="Search..." aria-describedby="basic-addon-search31" />
                                </div>
                            </div>--}}

                            <div class="d-flex justify-content-end">
                                <button class="generatePDF btn btn-outline-secondary text-nowrap disabled"
                                    title="{{__('locale.Please select a customer from your list first')}}">
                                    <div class="d-flex justify-content-center">
                                        <div class="align-content-center" style="margin-right: 3px;">
                                            <img src="{{ asset('assets/images/icon/icon-pdf.png') }}" width="15" height="15"
                                                alt="pdf-icon">
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>{{ __('locale.Print Protocol') }}</span>
                                            <div class="export-print-spinner d-none" style="position: absolute;">
                                                <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm"
                                                    style="margin-left: 20px;"></x-loading.ball-spin-clockwise>
                                            </div>
                                        </div>
                                    </div>
                                </button>

                                <div class="btn btn-outline-secondary total_duration">
                                    {{ __('locale.Duration with Interval', ['break' => '']) }}
                                    <br />
                                    <label>---</label>
                                </div>

                                <div class="btn btn-outline-secondary without_duration">
                                    {{ __('locale.Duration without Interval', ['break' => '']) }}
                                    <br />
                                    <label>---</label>
                                </div>

                                <div class="btn btn-outline-secondary total_pot" title="{{ __('locale.Planned Operating Time') }}">
                                    {{ __('locale.POT') }}
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#potInfoModel">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                                        </svg>
                                    </a>
                                    <br />
                                    <label>---</label>


                                    <div class="modal fade" id="potInfoModel" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalToggleLabel">{{ __("locale.Planned Operating Time") }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12 col-12">
                                                            <p>{{ __("locale.pot_info_line_1") }}</p>
                                                            <p>{{ __("locale.pot_info_line_2") }}</p>
                                                            <p>{{ __("locale.pot_info_line_3") }}</p>
                                                            <p>{{ __("locale.pot_info_line_4") }}</p>
                                                            <p><b>{{ __("locale.pot_info_line_5") }}</b></p>
                                                            <p>{{ __("locale.pot_info_line_6") }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="btn btn-outline-secondary total_amount">
                                    {{ __('locale.Amount') }}
                                    <br />
                                    <label>---</label>
                                </div>
                            </div>
                        @endrole
                    </div>
                </div>

                <div class="row">
                    {{-- Header third section --}}
                    <div class="d-flex flex-row mt-2 align-items-center justify-content-between ">

                        {{-- records per page dropdown --}}
                        <div class="px-1 width-align">
                            <label for="bulk" class="form-label invisible">{{__('locale.User')}}</label>
                            <select class="form-select form-select-sm print-view" id="bulk"
                                aria-label="Records per page">
                                <option value="100" {{ $perPage == 100 ? 'selected="true"' : '' }}>100</option>
                                <option value="200" {{ $perPage == 200 ? 'selected="true"' : '' }}>200</option>
                                <option value="300" {{ $perPage == 300 ? 'selected="true"' : '' }}>300</option>
                                {{-- https://team-1614110070467.atlassian.net/browse/TBL-456 --}}
                                {{-- <option value="100000000" {{ $perPage == 100000000 ? 'selected="true"' : '' }}>{{ __('locale.All') }}</option> --}}
                            </select>
                        </div>

                        {{-- customers dropdown --}}
                        <div class="px-1 width-align customer-custom-dropdown">
                            <label for="customers" class="form-label">{{ __('locale.Customer') }}</label>
                            <select class="select2 form-select form-select-sm" id="customers"
                                    aria-label="Select customer"
                                    data-edit-customer-route="{{ route('customers.show') }}"
                                    style="height:30px;">
                                <option value="all">{{ __('locale.All') }}</option>
                                @if(!empty($customers))
                                    @foreach($customers as $customer)
                                        <option class="option-chars-limited" value="{{ $customer->bdgogid }}" {{ ($groupId == $customer->bdgogid)
                                    ? "selected='true'" : "" }} data-customer-id="{{ $customer->id }}">
                                            {{ ucwords(strtolower($customer->customer_name)) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- users dropdown --}}
                        <div class="px-1 width-align" id="users_wrap">
                            @include('tenant.activity-overview.partials.users')
                        </div>

                        {{-- devices dropdown --}}
                        <div class="px-1 width-align" id="devices_wrap">
                            @include('tenant.activity-overview.partials.devices')
                        </div>

                        {{-- Contact type dropdown --}}
                        <div class="px-1 width-align">
                            <label for="contact_type" class="form-label">{{ __('locale.Contact Type') }}</label>
                            <select class="form-select form-select-sm print-view" id="contact_type"
                                aria-label="Select contact type">
                                <option value="" {{ $selectedContactType == '' ? 'selected="true"' : '' }}>{{ __('locale.All') }}</option>
                                <option value="1" {{ $selectedContactType == '1' ? 'selected="true"' : '' }}>{{ __('locale.Email') }}</option>
                                <option value="2" {{ $selectedContactType == '2' ? 'selected="true"' : '' }}>{{ __('locale.Phone') }}</option>
                                <option value="3" {{ $selectedContactType == '3' ? 'selected="true"' : '' }}>{{ __('locale.Video Call') }}</option>
                                <option value="4" {{ $selectedContactType == '4' ? 'selected="true"' : '' }}>{{ __('locale.On Site') }}</option>
                                <option value="5" {{ $selectedContactType == '5' ? 'selected="true"' : '' }}>{{ __('VPN') }}</option>
                                <option value="6" {{ $selectedContactType == '6' ? 'selected="true"' : '' }}>{{ __('Teamviewer') }}</option>
                            </select>
                        </div>

                        {{-- status dropdown --}}
                        <div class="px-1 width-align">
                            <label for="status" class="form-label">{{__('locale.Status')}}</label>
                            <select class="form-select form-select-sm print-view" id="status"
                                aria-label="Select status">
                                <option value="" {{ $selectedStatus == '' ? 'selected="true"' : '' }}>{{ __('locale.All') }}</option>
                                <option value="1" {{ $selectedStatus == '1' ? 'selected="true"' : '' }}>{{ __('locale.Charged') }}</option>
                                <option value="2" {{ $selectedStatus == '2' ? 'selected="true"' : '' }}>{{ __('locale.Not Charged') }}</option>
                                <option value="3" {{ $selectedStatus == '3' ? 'selected="true"' : '' }}>{{ __('locale.Hidden') }}</option>
                                <option value="4" {{ $selectedStatus == '4' ? 'selected="true"' : '' }}>{{ __('locale.Booked') }}</option>
                                <option value="8" {{ $selectedStatus == '8' ? 'selected="true"' : '' }}>{{ __('locale.Printed') }}</option>
                                <option value="5" {{ $selectedStatus == '5' ? 'selected="true"' : '' }}>{{ __('locale.Time Overlapping') }}</option>
                                <option value="6" {{ $selectedStatus == '6' ? 'selected="true"' : '' }}>{{ __('locale.Tariff Overlapping') }}</option>
                            </select>
                        </div>

                        {{-- tariffs dropdown --}}
                        @role('Admin')
                        <div class="px-1 width-align" id="tariffs_wrap">
                            @include('tenant.activity-overview.partials.tariffs')
                        </div>
                        @endrole

                        {{-- calendar dropdown --}}
                        <div class="px-1 width-align">
                            <label for="calendar_dropdown" class="form-label">{{ __('locale.Time') }}</label>
                            <select class="form-select form-select-sm" id="calendar_dropdown"
                                aria-label="Default select example">
                                <option value="" {{ ($selectedCalendar == '' || $selectedCalendar == 'all') ? 'selected="true"' : '' }}>{{ __('locale.All') }}</option>
                                <option value="1" {{ ((!$groupId && !($selectedCalendar == '' || $selectedCalendar == 'all')) || $selectedCalendar == '1') ? "selected='true'" : "" }}>{{ __('locale.Current Month') }}</option>
                                <option value="2" {{ $selectedCalendar == '2' ? 'selected="true"' : '' }}>{{ __('locale.Last Month') }}</option>
                                <option value="3" {{ $selectedCalendar == '3' ? 'selected="true"' : '' }}>{{ __('locale.This Quarter') }}</option>
                                <option value="4" {{ $selectedCalendar == '4' ? 'selected="true"' : '' }}>{{ __('locale.Last Quarter') }}</option>
                                <option value="5" {{ $selectedCalendar == '5' ? 'selected="true"' : '' }}>{{ __('locale.This Year') }}</option>
                                <option value="6" {{ $selectedCalendar == '6' ? 'selected="true"' : '' }}>{{ __('locale.Last Year') }}</option>
                            </select>
                        </div>

                        {{-- start date --}}
                        <div class="px-1 width-align dw t-space">
                            <label for="start_date" class="visually-hidden">{{ __('locale.Start Date') }}</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-calendar-check"></i></span>
                                <input type="text" class="form-control" placeholder="{{ __('locale.Start') }}"
                                    aria-label="{{ __('locale.Start') }}" aria-describedby="start_date" id="start_date"
                                    onchange="this.dispatchEvent(new InputEvent('input'))">
                            </div>

                        </div>

                        {{-- end date --}}
                        <div class="px-1 width-align dw t-space">
                            <label for="end_date" class="visually-hidden">{{ __('locale.End Date') }}</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-calendar-check"></i></span>
                                <input type="text" class="form-control" placeholder="{{ __('locale.End') }}"
                                    aria-label="{{ __('locale.End') }}" aria-describedby="end_date" id="end_date"
                                    onchange="this.dispatchEvent(new InputEvent('input'))">
                            </div>
                        </div>

                        <div class="px-1 width-align small-icon-w t-space">
                            <label for="clear_filters" class="form-label invisible">T</label>
                            <button id="clear_filters" class="btn btn-sm btn-outline-dark btn-icon"
                                style="padding: 11px;" title="{{ __('locale.Clear all filters') }}">
                                <span><i width="15" height="15" data-feather="x"></i></span>
                            </button>
                        </div>

                    </div>


                    {{-- inputs: sort, printview --}}
                    <input type="hidden" id="sort_column" name="sort_column" value="start_date">
                    <input type="hidden" id="sort_direction" name="sort_direction" value="desc">

                    @if(!$printView)
                    <div class="table-responsive text-nowrap table-ao" id="connection_report_table">
                        {{-- spinner before loading connections --}}
                        <div class="text-center pt-2">
                            <div class="div-button-loading-state-custom" style="color: black;width: 100%;height: 100%;">
                                <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm mx-auto"
                                    style="color: #495057;">
                                </x-loading.ball-spin-clockwise>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="pt-1">
                        @livewire('tenant.connection-print-view-component',['selectedCustomer' => $selectedCustomer,
                        'selectedCalendar' => $selectedCalendar, 'start_date' => $start_date, 'end_date' =>
                        $end_date],key($selectedCustomer .'-'. time()))
                    </div>
                    @endif

                    {{--Connection Splitting Modal--}}
                    <div class="modal fade" id="splittingModal" tabindex="-1" aria-labelledby="splittingModalLabel"
                        aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                @livewire('tenant.splitting-component')
                            </div>
                        </div>
                    </div>

                    {{--Connection Overlaps Modal--}}
                    <div class="modal fade" id="overlapsModal" tabindex="-1" aria-labelledby="overlapsModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                @livewire('tenant.connection-overlaps-component')
                            </div>
                        </div>
                    </div>

                    {{--Add manual activity--}}
                    <div class="modal fade" id="manualActivityModal" tabindex="-1"
                        aria-labelledby="manualActivityModalLabel" aria-hidden="true" data-backdrop="static"
                        data-keyboard="false" wire:key="manual-activity-modal">
                        {{--<div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                --}}{{-- @livewire('tenant.manual-activity-component')--}}{{--
                            </div>
                        </div>--}}
                    </div>

                    {{-- Add User Modal --}}
                    <div class="modal fade custom-modal-cstm" id="addUserModal" tabindex="-1"
                        aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static"
                        data-keyboard="false" wire:key="add-user-modal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                {{-- @livewire('tenant.user-modal-component',['customer' => $customer], key($customer ?
                                'user-modal-'.$customer->id : 'user-modal-123456'))--}}
                            </div>
                        </div>
                    </div>

                    {{-- User Modal on edit connection --}}
                    <div class="modal fade custom-modal-cstm" id="addUserModalEditConnection" tabindex="-1"
                        aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static"
                        data-keyboard="false" wire:key="add-user-modal-edit-connection">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                {{-- @livewire('tenant.user-modal-component',['customer' =>
                                $customer,'actionType'=>'addUserModalEditConnection'], key($customer ?
                                'user-modal-edit-connection-'.$customer->id : 'user-modal-edit-connection-123456'))--}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('vendor-script')
{{-- vendor scripts here --}}
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>

@endsection

@section('custom_scripts')
    <script src="{{asset('assets/js/forms-selects.js')}}"></script>
    <script>
    // handle livewire hooks
        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.sent', (message, component) => {
                if (message.updateQueue[0].method === 'openActivityModal' || message.updateQueue[0].method === 'openOverlapsModal'
                    || message.updateQueue[0].method === 'splittingConnection') {
                    $('.button-row-conn-loading-state').attr('disabled', 'disabled');
                    $('.bill-checkbox-fieldset').attr('disabled', 'disabled');
                    $('.row-checkbox-fieldset').attr('disabled', 'disabled');
                    $('.row-image-connection-visible-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                    $('.row-image-connection-invisible-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                    $('#dots-icon-for-buttons-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                    $('.div-button-loading-state-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                } else if (message.updateQueue[0].payload.method === 'checkboxSelected') {
                    $('.div-row-checkbox-fieldset-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                    $('.row-checkbox-connection-invisible-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                }
            });
            Livewire.hook('message.processed', (message, component) => {
                if (message.updateQueue[0].method === 'openActivityModal' || message.updateQueue[0].method === 'openOverlapsModal'
                    || message.updateQueue[0].method === 'splittingConnection') {
                    $('.button-row-conn-loading-state').removeAttr('disabled', 'disabled');
                    $('.bill-checkbox-fieldset').removeAttr('disabled', 'disabled');
                    $('.row-checkbox-fieldset').removeAttr('disabled', 'disabled');
                    $('.row-image-connection-visible-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                    $('.row-image-connection-invisible-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                    $('#dots-icon-for-buttons-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                    $('.div-button-loading-state-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                } else if (message.updateQueue[0].payload.method === 'checkboxSelected') {
                    $('.div-row-checkbox-fieldset-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                    $('.row-checkbox-connection-invisible-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                } else if (message.updateQueue[0].payload.method === 'gotoPage') {
                    $(window).scrollTop(0);
                }
            });
            Livewire.hook('message.sent', (message, component) => {
                if (message.updateQueue[0].method === 'changeBillColumn') {
                    $('.button-row-conn-loading-state').attr('disabled', 'disabled');
                }
            });
            Livewire.hook('message.processed', (message, component) => {
                if (message.updateQueue[0].method === 'changeBillColumn') {
                    $('.button-row-conn-loading-state').removeAttr('disabled', 'disabled');
                }
            });
            Livewire.hook('message.sent', (message, component) => {
                if (message.updateQueue[0].payload.event === 'refresh') {
                    $('.button-row-conn-loading-state').attr('disabled', 'disabled');
                    $('.bill-checkbox-fieldset').attr('disabled', 'disabled');
                }
            });
            Livewire.hook('message.processed', (message, component) => {
                if (message.updateQueue[0].payload.event === 'refresh') {
                    $('.button-row-conn-loading-state').removeAttr('disabled', 'disabled');
                    $('.bill-checkbox-fieldset').removeAttr('disabled', 'disabled');
                }
            });

            /*Livewire.hook('message.sent', (message,component) => {
                if (message.updateQueue[0].payload.method === 'loadMoreForConnectionsReport') {
                    $('#loading-text-spinner').text('{{ __('locale.Loading More') }}');
                    $('#div-load-more-spinner').removeClass('invisible');
                }
            });
            Livewire.hook('message.processed', (message,component) => {
                if (message.updateQueue[0].payload.method === 'loadMoreForConnectionsReport') {
                    $('#div-load-more-spinner').addClass('invisible');
                }
            });*/
            Livewire.hook('message.received', (message, component) => {
                if (message.updateQueue[0].method === 'startChronos') {
                    window.livewire.emitTo('tenant.partials.timer-nav-database-component', 'startChronosTimerComponent');
                }
                // else if (message.updateQueue[0].payload.event === 'closeManualActivityModal') {
                //     console.log('close manual activity modal');
                //     window.livewire.emitTo('tenant.partials.timer-nav-database-component','componentUnmount');
                // }
            });
            Livewire.hook('message.sent', (message, component) => {
                if (message.updateQueue[0].payload.event === 'stopChronoValue' || message.updateQueue[0].payload.event === 'addManualActivity') {
                    $('#chrono_actions_button').addClass('d-none');
                    $('#chrono_loading_manual_modal_button').removeClass('d-none');

                    $('#loading-spinner-for-nav-timer-icon-button').addClass('d-none');
                    $('#loading-spinner-for-nav-timer-button').removeClass('d-none');
                }
            });
            Livewire.hook('message.processed', (message, component) => {
                if (message.updateQueue[0].payload.event === 'stopChronoValue' || message.updateQueue[0].payload.event === 'addManualActivity') {
                    $('#chrono_actions_button').removeClass('d-none');
                    $('#chrono_loading_manual_modal_button').addClass('d-none');

                    $('#loading-spinner-for-nav-timer-icon-button').removeClass('d-none');
                    $('#loading-spinner-for-nav-timer-button').addClass('d-none');
                }
            });
        });

        $(document).ready(function () {
            let $locale = "{!! config('app.locale') !!}",
                calendarDropdown = "{{ $selected_calendar }}";

            // page init
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            getConnectionReports();

            window.initDatePickerManualActivity = () => {
                /*if ($locale === "en") {
                    $('.date_time_manual_activity').pickadate({
                        format: "dd.mm.yyyy",
                        firstDay: 1,
                        clear: false,
                    });
                } else {
                    $('.date_time_manual_activity').pickadate({
                        format: "dd.mm.yyyy",
                        firstDay: 1,
                        monthsFull: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
                        weekdaysFull: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
                        weekdaysShort: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
                        today: 'Heute',
                        close: 'Schließen',
                        clear: false,
                    });
                }*/
            };
            initDatePickerManualActivity();
            window.livewire.on('initDatePickerManualActivity', () => {
                initDatePickerManualActivity();
            });
            window.livewire.emit('redirectToastrMessages');
            window.livewire.emitTo('tenant.partials.timer-nav-database-component', 'stopChronoRedirected');
            window.livewire.emit('recovery_connection_process');
            window.addEventListener('load_flatpickrs', event => {
                let start = event.detail.start;
                let end = event.detail.end;
                let startElem = $('#start_date');
                let endElem = $('#end_date');
                if ($locale_sys === 'de') {
                    flatpickr.localize(flatpickr.l10ns.de);
                    startElem.flatpickr({
                        enableTime: true,
                        time_24hr: true,
                        minuteIncrement: 1,
                        dateFormat: "d.m.Y H:i",
                        defaultDate: start,
                        locale: {
                            "locale": "de",
                            firstDayOfWeek: 1
                        },
                    });

                    endElem.flatpickr({
                        enableTime: true,
                        time_24hr: true,
                        minuteIncrement: 1,
                        dateFormat: "d.m.Y H:i",
                        defaultDate: end,
                        locale: {
                            "locale": "de",
                            firstDayOfWeek: 1
                        },
                    });
                } else {
                    startElem.flatpickr({
                            enableTime: true,
                            time_24hr: true,
                            minuteIncrement: 1,
                            dateFormat: "d.m.Y H:i",
                            defaultDate: start,
                            locale: {
                                firstDayOfWeek: 1
                            },
                        }
                    );
                    endElem.flatpickr({
                            enableTime: true,
                            time_24hr: true,
                            minuteIncrement: 1,
                            dateFormat: "d.m.Y H:i",
                            defaultDate: end,
                            locale: {
                                firstDayOfWeek: 1
                            },
                        }
                    );
                }
            });
            window.initDatePicker = () => {
                if ($locale === "en") {
                    flatpickr($('#start_date'), {
                        dateFormat: "d.m.Y",
                    });
                    flatpickr($('#end_date'), {
                        dateFormat: "d.m.Y",
                    });
                } else {
                    flatpickr($('#start_date'), {
                        "locale": "de",
                        dateFormat: "d.m.Y",
                    });
                    flatpickr($('#end_date'), {
                        "locale": "de",
                        dateFormat: "d.m.Y",
                    });
                }
                /*if ($locale_sys === "en") {
                    $('.pickadate_start,.pickadate_end').pickadate({
                        format: "dd.mm.yyyy",
                        firstDay: 1,
                        weekdaysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                        today: false,
                    });
                } else {
                    $('.pickadate_start,.pickadate_end').pickadate({
                        format: "dd.mm.yyyy",
                        firstDay: 1,
                        monthsFull: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
                        weekdaysFull: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
                        weekdaysShort: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
                        today: false,
                        clear: 'Klar',
                        close: 'Schließen',
                    });
                }*/
            };
            initDatePicker();

            window.addEventListener('setDateFlatPicker', event => {
                let startDate = document.querySelector('#start_date_manual_activity');
                let endDate = document.querySelector('#end_date_manual_activity');

                if ($locale == 'de') {
                    flatpickr.localize(flatpickr.l10ns.de);
                    startDate.flatpickr({
                        dateFormat: "d.m.Y",
                        defaultDate: event.detail.start_date,
                        locale: {
                            "locale": $locale,
                        },
                    });
                    endDate.flatpickr({
                        dateFormat: "d.m.Y",
                        defaultDate: event.detail.end_date,
                        locale: {
                            "locale": $locale,
                        },
                    });
                } else {
                    startDate.flatpickr({
                        dateFormat: "d.m.Y",
                        defaultDate: event.detail.start_date,
                    });
                    endDate.flatpickr({
                        dateFormat: "d.m.Y",
                        defaultDate: event.detail.end_date,
                    });
                }
            });

            // per page count change
            $(document).on('change', '#bulk', function (event) {
                getConnectionReports();
            });

            // customer change
            $(document).on('change', '#customers', function (event) {
                let text = '';
                let visibility = 'hidden';

                if (event.target.value != '' && event.target.value != 'all') {
                    text = $('#customers option[value="' + event.target.value + '"]').text();
                    visibility = 'unset';
                    $('.print-view').removeAttr('title');
                } else {
                    $('#customSwitchPrintView').prop('checked', false).attr('disabled', false);
                    $('.print-view').removeClass('enabled').attr('disabled', false);
                    $('.print-view').attr('title', "{{ __('locale.Please close the print view first') }}");
                }

                $("#customer_name_title h5 strong").text(text).parent().parent().css('visibility', visibility);

                if (visibility == 'unset') {
                    $(document).find("#customer_name_title").find("a#customer_edit_link").attr("href", $(event.target).data("edit-customer-route") + "/" + $(event.target).find(':selected').data("customer-id"));
                } else {
                    $(document).find("#customer_name_title").find("a#customer_edit_link").attr("href", "javascript:void(0);");
                }

                if (!$(this).val()) {
                    $('.edit-customer-btn').attr('disabled', true);
                }

                getUsersByCustomer();
                getDevicesByCustomer();
                getTariffsByCustomer();
                getConnectionReports();
            });

            // show time overlapped connection modal
            $(document).on('click', '.time-overlapped-connection', function (event) {
                let connectionId = $(this).attr('data-connection');
                window.livewire.emitTo('tenant.connection-overlaps-component', 'selectedItem', connectionId);
            });

            window.addEventListener('openOverlapsModal', event => {
                $('#overlapsModal').modal('show');
            });
            window.addEventListener('closedOverlapsModal', event => {
                $('#overlapsModal').modal('hide');
            });

            // toggle confirm connection tariff modal
            $(document).on('click', '.confirm-connection-tariff', function (event) {
                let connectionId = $(this).attr('data-connection');
                window.livewire.emit('showModal', 'tenant.confirm-component', connectionId);
            });

            // show edit connection form
            $(document).on('click', '.split-connection', function (event) {
                let connectionId = $(this).attr('data-connection');
                window.livewire.emitTo('tenant.splitting-component', 'setConnection', connectionId);
            });

            // toggle split connection modal
            window.addEventListener('openSplittingModal', event => {
                $('#splittingModal').modal('show');
            });
            window.addEventListener('closeSplittingModal', event => {
                $('#splittingModal').modal('hide');
            });

            // show toast alerts: success,errors & warning.
            window.addEventListener('showToastrSuccess', event => {
                toastr.success('', event.detail.message).css("width", "fit-content")
            });

            window.addEventListener('showToastrError', event => {
                toastr.error('', event.detail.message).css("width", "fit-content")
            });

            window.addEventListener('showToastrWarning', event => {
                toastr.warning('', event.detail.message).css("width", "fit-content");
            });

            // refresh connection reports
            window.addEventListener('refreshConnectionReports', event => {
                getConnectionReports();
            });

            // edit connection
            $(document).on('click', '.edit-connection-item', function (event) {
                let connectionId = $(this).attr('data-connection');
                let type = $(this).attr('data-connection-type');

                let customer = $('#customers').val();

                // showModal via livewire component
                window.livewire.emit('showModal', 'tenant.activity-form-component', type, JSON.stringify({
                    item: connectionId,
                    customer: customer
                }));

                // showModal via ajax request.
                /*let connectionId = $(this).attr('data-connection');
                let type = $(this).attr('data-connection-type');
                $.ajax({
                    url: "{{route('customer.connection.edit')}}"+'/'+type+'/'+connectionId,
                    type: 'GET',
                    success: function (data) {
                        console.log('success data => ', data);
                        $('#manualActivityModal').html(data.html);
                        $('#manualActivityModal').modal("show");
                    },
                    failure: function (i, x, r) {
                        console.log('failure =>', i, x, r);
                    }
                });*/
            });

            // rest of filters
            $(document).on('change', '#users,#devices,#contact_type,#status,#tariffs', function (event) {
                getConnectionReports();
            });

            var is_calendar_change = false;
            // calendar
            $(document).on('change', '#calendar_dropdown', function (event) {
                $('#start_date').val(null);
                $('#end_date').val(null);
                is_calendar_change = true;
                getConnectionReports(1, true);
            });

            // on search term
            $(document).on('keypress', '#search_term', function (event) {
                getConnectionReports();
            });

            // on customer edit
            $(document).on('click', '.edit-customer-btn', function (event) {
                if ($('#customers').val()) {
                    let customerId = $(this).attr('data-customer-id');
                    if (customerId) {
                        window.location.replace("{{route('customers.show')}}" + '/' + customerId);
                    }
                }
            });

            //
            $(document).on('click', '.bulk-action', function (event) {
                let data_action_type = $(this).attr('data-action-type');
                let selected_connections = [];
                $('.customer-connections-common tbody .select-row-checkbox:checked').each(function (index, item) {
                    selected_connections.push($(item).val());
                });
                updateBulkConnectionStatus(data_action_type, selected_connections);
            });

            // reset filters
            $(document).on('click', '#clear_filters', function (event) {
                $(this).find('i').addClass('hidden');
                $(this).find('.loader').removeClass('hidden');
                $('#pagination').val(1);
                $('#bulk').val(100);
                $('#customers').val(null);
                $('#users').val(null);
                $('#devices').val(null);
                $('#contact_type').val(null);
                $('#status').val(null);
                $('#tariffs').val(null);
                $('#search_term').val(null);
                $('#calendar_dropdown').val(1);
                $('#start_date').val();
                $('#end_date').val();
                $('#sort_column').val('start_date');
                $('#sort_direction').val('desc');
                getConnectionReports();
                is_calendar_change = false;
            });

            $(document).on('click', '.sortable_column', function (event) {
                let column_name = $(this).attr('data-sort-column');
                let direction = $(this).attr('data-current-sort-direction') == 'desc' ? 'asc' : 'desc';
                $('#sort_column').val(column_name);
                $('#sort_direction').val(direction);
                getConnectionReports();
            });


            // rerender on print view toggle
            $(document).on('change', '#customSwitchPrintView', function (event) {
                if ($(this).is(':checked')) {
                    $('.print-view').attr('title', "{{ __('locale.Please close the print view first') }}");
                    $('#chrono_actions_button').attr('disabled', true);
                } else {
                    $('.print-view').removeAttr('title');
                    $('#chrono_actions_button').attr('disabled', false);
                }
                getConnectionReports();
            });

            $(document).on('click', '.change-bill-checkbox', function (event) {
                let id = $(this).attr('data-connection');
                $('#change-bill-' + id).addClass('d-none');
                $('#change-bill-spinner-' + id).removeClass('d-none');
                toggleBillCharge(id);
                getConnectionReports();
            });

            // TODO:: fix calendar issue on date change
            $(document).on('change', '#start_date,#end_date', function (event) {
                is_calendar_change = false;
                $('#calendar_dropdown').val(null);
                getConnectionReports();
            });

            // handle pagination via ajax
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchPaginatedData(page);
            });

            // handle global checkbox
            $(document).on('click', '#row_checkbox_connection_main', function (event) {
                $('.customer-connections-common tbody .select-row-checkbox').prop('checked', $(this).is(':checked'));
                if ($(this).is(':checked')) {
                    $('.custom-action-all').removeClass('d-none');
                    $('.dropdownColumn.dropdown').addClass('d-none');
                    $('.actions-column').css("padding", "0px");
                    elements = $('.actions-column .hiddenIcon');
                    elements.each(function () {
                        $(this).parents("td").css("padding", "1rem");
                    });
                    $(document).find(".custom-action-all-th").fadeOut(100);
                } else {
                    $('.custom-action-all').addClass('d-none');
                    $('.dropdownColumn.dropdown').removeClass('d-none');
                    $('.actions-column').css("padding", "0.2rem 0");
                    $(document).find(".custom-action-all-th").fadeIn(200);
                }
            });

            // handle individual checkbox
            $(document).on('click', '.select-row-checkbox', function (event) {
                let total_rows = $('.customer-connections-common tbody .select-row-checkbox').length;
                let total_checked_rows = 0;
                $('.customer-connections-common tbody .select-row-checkbox').each(function (index, item) {
                    total_checked_rows += $(item).is(':checked') ? 1 : 0;
                });
                (total_rows === total_checked_rows) ? $('#row_checkbox_connection_main').prop('checked', true) : $('#row_checkbox_connection_main').prop('checked', false);
                // (total_checked_rows > 0) ? $('.custom-action-all').addClass('d-flex').removeClass('d-none')
                //     : $('.custom-action-all').addClass('d-none').removeClass('d-flex');

                if (total_checked_rows > 0) {
                    $('.custom-action-all').removeClass('d-none');
                    $('.dropdownColumn.dropdown').addClass('d-none');
                    $('.actions-column').css("padding", "0px");
                    elements = $('.actions-column .hiddenIcon');
                    elements.each(function () {
                        $(this).parents("td").css("padding", "1rem");
                    });
                    $(document).find(".custom-action-all-th").fadeOut(100);
                } else {
                    $('.custom-action-all').addClass('d-none');
                    $('.dropdownColumn.dropdown').removeClass('d-none');
                    $('.actions-column').css("padding", "0.2rem 0");
                    $(document).find(".custom-action-all-th").fadeIn(200);
                }
            });


            // handle pdf export via ajax
            $(document).on('click', '.generatePDF', function (event) {
                if ($('.generatePDF').hasClass('disabled')) {
                    return false;
                }
                $('.generatePDF').addClass('disabled');
                $('.generatePDF span').css('visibility', 'hidden');
                $('.generatePDF .export-print-spinner').removeClass('d-none');
                let formData = new FormData();
                formData.append('selected_customer', $('#customers').val());
                formData.append('selected_user', $('#users').val());
                formData.append('selected_device', $('#devices').val());
                formData.append('selected_contact_type', $('#contact_type').val());
                formData.append('selected_status', $('#status').val());
                formData.append('selected_calendar', $('#calendar_dropdown').val());
                if ($('#customers').val() == '' || $('#customers').val() == 'all') {
                    formData.append('start_date', $('#start_date').val());
                    formData.append('end_date', $('#end_date').val());
                }
                if ($('#tariffs').length) {
                    formData.append('selected_tariff', $('#tariffs').val());
                }
                if ($('#search_term').length) {
                    formData.append('search_term', $('#search_term').val());
                }

                $.ajax({
                    url: "{{route('customer.connections.export.pdf')}}",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response, status, xhr) {

                        var filename = "export_protocol.pdf";
                        var disposition = xhr.getResponseHeader('Content-Disposition');

                        if (disposition) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            // console.log('filenameRegex => ', filenameRegex);
                            var matches = filenameRegex.exec(disposition);
                            if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                        }
                        var linkelem = document.createElement('a');
                        try {
                            var blob = new Blob([response], {type: 'application/pdf'});
                            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                window.navigator.msSaveBlob(blob, filename);
                            } else {
                                var URL = window.URL || window.webkitURL;
                                var downloadUrl = URL.createObjectURL(blob);

                                if (filename) {
                                    // use HTML5 a[download] attribute to specify filename
                                    var a = document.createElement("a");

                                    // safari doesn't support this yet
                                    if (typeof a.download === 'undefined') {
                                        window.location = downloadUrl;
                                    } else {
                                        a.href = downloadUrl;
                                        document.body.appendChild(a);
                                        a.target = "_blank";
                                        a.click();
                                    }
                                } else {
                                    window.location = downloadUrl;
                                }
                            }

                        } catch (ex) {
                            console.log(ex);
                        }
                        $('.generatePDF').removeClass('disabled');
                        $('.generatePDF span').css('visibility', 'unset');
                        $('.generatePDF .export-print-spinner').addClass('d-none');
                    },
                    failure: function (i, x, r) {
                        console.log('failure =>', i, x, r);
                        $('.generatePDF').removeClass('disabled');
                        $('.generatePDF span').css('visibility', 'unset');
                        $('.generatePDF .export-print-spinner').addClass('d-none');
                    }
                });
            });

            // infinite scroll pagination
            let pagination_in_progress = false;
            let last_page = 1;
            window.onscroll = function (ev) {
                if (((window.innerHeight + window.scrollY) >= $(document).height() - 1000)
                    && !pagination_in_progress && $('#bulk').val() > 1000) {
                    if ($('.load-more-spinner').length) {
                        pagination_in_progress = true;
                        last_page += 1;
                        getConnectionReports(last_page);
                    }
                }
            };

            // Allow only - _ and .
            // For Topic input.
            window.addEventListener('initTopicCharVal', event => {
                $(document).find("#topic_manual_activity").unbind().on("keydown", function(event) {
                    let regex = new RegExp("^[a-zA-Z0-9-._]*$"),
                        key = event.key;

                    if (!regex.test(key)) {
                        event.preventDefault();

                        return false;
                    }
                });
            });

            function toggleBillCharge(connectionId) {
                $.ajax({
                    url: "{{route('customer.connection.report.change.billing')}}",
                    type: 'POST',
                    data: {conn_id: connectionId},
                    cache: false,
                    success: function (response) {
                        $('#change-bill-spinner-' + connectionId).addClass('d-none');
                        $('#change-bill-' + connectionId).removeClass('d-none');
                        if ($('#customSwitchPrintView').is(':checked')) {
                            getConnectionReports();
                        }
                    },
                    failure: function (i, x, r) {
                        console.log('failure =>', i, x, r);
                    }
                });
            }

            function fetchPaginatedData(page) {
                getConnectionReports(page);
            }

            function getUsersByCustomer() {
                let customer = $('#customers').val()
                $.ajax({
                    url: "{{route('get.users.by.customer')}}/" + (customer ? customer : ''),
                    type: 'GET',
                    success: function (data) {
                        $('#users_wrap').html(data.html);
                    },
                    failure: function (i, x, r) {
                        console.log('failure =>', i, x, r);
                    }
                });
            }

            function getDevicesByCustomer() {
                let customer = $('#customers').val()
                $.ajax({
                    url: "{{route('get.devices.by.customer')}}/" + (customer ? customer : ''),
                    type: 'GET',
                    success: function (data) {
                        $('#devices_wrap').html(data.html);
                    },
                    failure: function (i, x, r) {
                        console.log('failure =>', i, x, r);
                    }
                });
            }

            function getTariffsByCustomer() {
                let customer = $('#customers').val()
                $.ajax({
                    url: "{{route('get.tariffs.by.customer')}}/" + (customer ? customer : ''),
                    type: 'GET',
                    success: function (data) {
                        $('#tariffs_wrap').html(data.html);
                    },
                    failure: function (i, x, r) {
                        console.log('failure =>', i, x, r);
                    }
                });
            }

            var currentConnectionReportXHR = null;

            function getConnectionReports(pageNumber = 1, isCalendarDrp = false) {
                $('.filter-processing-loader').fadeIn();
                var formData = new FormData();
                formData.append('per_page', $('#bulk').val());
                formData.append('selected_customer', $('#customers').val());
                formData.append('selected_user', $('#users').val());
                formData.append('selected_device', $('#devices').val());
                formData.append('selected_contact_type', $('#contact_type').val());
                formData.append('selected_status', $('#status').val());
                if ($('#tariffs').length) {
                    formData.append('selected_tariff', $('#tariffs').val());
                }
                if ($('#search_term').length) {
                    formData.append('search_term', $('#search_term').val());
                }
                formData.append('selected_calendar', $('#calendar_dropdown').val());

                if ($('#customers').val() == '' || $('#customers').val() == 'all') {
                    if (calendarDropdown == 'all') {
                        $("#calendar_dropdown").val($("#calendar_dropdown option:first").val());
                    }

                    formData.append('start_date', $('#start_date').val());
                    formData.append('end_date', $('#end_date').val());
                } else {
                    let customerName = $('#customers option[value="' + $('#customers').val() + '"]').text();
                    $("#customer_name_title h5 strong").text(customerName).parent().parent().css('visibility', 'unset');
                    $(document).find("#customer_name_title").find("a#customer_edit_link").attr("href", $('#customers').data("edit-customer-route") + "/" + $('#customers').find(':selected').data("customer-id"));
                }

                formData.append('sort_column', $(document).find('#sort_column').val());
                formData.append('sort_direction', $(document).find('#sort_direction').val());
                formData.append('print_view', $(document).find('#customSwitchPrintView').is(':checked'));
                formData.append('page_number', pageNumber);

                currentConnectionReportXHR = $.ajax({
                    url: "{{route('customer.get.connection.reports.by.filter', $groupId)}}",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    beforeSend : function() {
                        if (currentConnectionReportXHR != null) {
                            currentConnectionReportXHR.abort();
                        }
                    },
                    success: function (data) {
                        $('.filter-processing-loader').fadeOut();
                        if (data.is_bulk && pageNumber > 1) {
                            $('#connection_report_table tbody').append(data.html);
                            $('#connection_report_table_new tbody').append(data.html);
                            if (data.show_loader) {
                                pagination_in_progress = false;
                                $('.load-more-spinner').fadeIn();
                            } else {
                                pagination_in_progress = true;
                                $('.load-more-spinner').fadeOut();
                            }
                        } else {
                            pagination_in_progress = false;
                            last_page = 1;
                            $('#connection_report_table').html(data.html);
                            $('#connection_report_table_new').html(data.html);
                        }
                        if (data.selected_calendar) {
                            if (!is_calendar_change && !$('#customers').val()) {
                                if (calendarDropdown == 'all') {
                                    $("#calendar_dropdown").val($("#calendar_dropdown option:first").val());
                                } else {
                                    $('#calendar_dropdown').val(data.selected_calendar);
                                }
                            }
                        }
                        // set date ranges
                        if (!$('#customers').val()) {
                            $('#start_date').val(data.start_date);
                            $('#end_date').val(data.end_date);
                        }

                        if (isCalendarDrp) {
                            $('#start_date').val("");
                            $('#end_date').val("");
                        }

                        // set totals
                        $('.total_duration').find('label').html(data.totalDuration);
                        $('.without_duration').find('label').html(data.withoutInterval);
                        $('.total_pot').find('label').html(data.totalPOT);
                        $('.total_amount').find('label').html(data.totalAmount);

                        let title = $('#customers').val()
                            ? "{{ __('locale.Add a new manual activity') }}"
                            : "{{ __('locale.Please select a customer from your list first') }}";
                        let isAddConnectionEnabled = true;

                        if (data.print_view) {
                            isAddConnectionEnabled = false;
                            title = "{{ __('locale.Please close the print view first') }}";
                            $('.print-view').addClass('enabled').attr('disabled', true);
                            $('.print-view').val(null);
                            $('#bulk').val(100000000);
                            $('#chrono_actions_button').attr('disabled', true);
                        }
                        $('.add-connection-manually').attr('disabled', !isAddConnectionEnabled);

                        // customer edit button & crono button
                        let customerTitle = "{{__('locale.Please select a customer from your list first')}}";
                        let cronoTitle = "{{__('locale.Please select a customer from your list first')}}";

                        if ($('#customers').val() && !data.print_view) {
                            customerTitle = "{{ __('locale.Edit Customer') }}";
                            cronoTitle = "{{ __('locale.Launch') }}";
                            $('.edit-customer-btn').addClass('cursor-pointer');
                            $('.edit-customer-btn').attr('disabled', false);
                            $('.start-crono-btn').attr('disabled', false);
                            $('.print-view').removeClass('enabled').attr('disabled', false);
                            $('#chrono_actions_button').attr('disabled', false);
                        } else {
                            if (data.print_view == "true") {
                                customerTitle = "{{ __('locale.Please close the print view first') }}";
                                cronoTitle = "{{ __('locale.Please close the print view first') }}";
                                $('.start-crono-btn').attr('disabled', true);
                            }
                            $('.edit-customer-btn').removeClass('cursor-pointer');
                            $('.edit-customer-btn').attr('disabled', true);
                        }
                        $('.edit-customer-btn').attr('title', customerTitle);
                        if (data.customer && data.customer.id) {
                            $('.edit-customer-btn').attr('data-customer-id', data.customer.id);
                            $('#customSwitchPrintView').attr('disabled', false);
                        } else {
                            $('.edit-customer-btn').removeAttr('data-customer-id');
                            $('#customSwitchPrintView').attr('disabled', true);
                        }

                        if ("{{ session()->has('recovery_connection_process')}}") {
                            $('.stop-crono-btn').removeClass('d-none');
                            $('.start-crono-btn').addClass('d-none');
                        } else {
                            $('.stop-crono-btn').addClass('d-none');
                            $('.start-crono-btn').removeClass('d-none');
                        }

                        if ($('#customers').val() && $('#customers').val() !== 'all') {
                            $('.generatePDF').removeClass('disabled')
                                .attr('title', "{{ __('locale.Print Protocol') }}");
                        } else {
                            $('.generatePDF').addClass('disabled');

                            $('.generatePDF')
                                .attr('title', "{{ __('locale.Please select a customer from your list first') }}");
                        }

                        if (data.out_calendar_range) {
                            $('#calendar_dropdown').attr('disabled', true);
                        } else {
                            $('#calendar_dropdown').removeAttr('disabled');
                        }
                        $(document).find('#clear_filters .loader').addClass('hidden');
                        $(document).find('#clear_filters i').removeClass('hidden');
                        toggleLaunchButton();

                        // init feather
                        feather.replace();

                        // Init bootstrap tooltip.
                        $(document).find('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();
                    },
                    failure: function (i, x, r) {
                        console.log('failure =>', i, x, r);
                    }
                });
            }

            function updateBulkConnectionStatus(selectedStatus, selectedConnections) {
                var formData = new FormData();
                formData.append('selected_connections', selectedConnections);
                formData.append('selected_status', selectedStatus);

                $.ajax({
                    url: "{{route('customer.connection.bulk.status')}}",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        getConnectionReports();
                        toastr.success('', "{{__('locale.Connection Updated!')}}").css("width", "fit-content")
                    },
                    failure: function (i, x, r) {
                        toastr.error('', "{{__('locale.Error')}}").css("width", "fit-content")
                        console.log('failure =>', i, x, r);
                    }
                });
            }

            function toggleLaunchButton() {
                let cronosIsInProgress = $('#nav-stop-timer').length > 0;
                if (cronosIsInProgress) {
                    $('.start-crono-btn').addClass('d-none');
                    $('.stop-crono-btn').removeClass('d-none');
                } else {
                    $('.stop-crono-btn').addClass('d-none');
                    $('.start-crono-btn').removeClass('d-none');
                }
            }
        });
</script>
@endsection

{{--PRINT window tab--}}
{{--1. print only monlthy data for AO --}}
{{--OR 1. print only quarterly data for AO --}}

{{--TBL-409 --}}
{{--part-1--}} {{--- for note only--}}
{{-- notice field is changeable --}}

{{--part-2--}}


