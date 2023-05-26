@section('title', __('locale.Connections'))

@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
@section('custom_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{asset('/frontend/css/connections_component.css?v=').time()}}">
    <link rel="stylesheet" href="{{asset('/frontend/css/connection_edit_component_css.css')}}">
    <link rel="stylesheet" href="{{asset('/frontend/css/loading_states_awesome.css?v=').time()}}">
    <link rel="stylesheet" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/06c76628af.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>

    <style>
        .custom-action-all-td{
            position: relative;
        }
        .custom-action-all{
            position: absolute;
            top: -25px;
            left: 0px;
            right: 0px;
        }
        html .content.app-content {
            overflow: initial;
        }
        .checkbox-lg label::before, .checkbox-lg label::after {
            width: 18px;
            height: 18px;
        }
        .modal {
            overflow: auto !important;
        }
        .picker {
            font-size: 10px;
            width: 200px;
        }
        .flatpickr-calendar {
            width: 220px;
        }
        .flatpickr-calendar,.flatpickr-weekday,.numInputWrapper  {
            font-size: 10px;
        }
        .flatpickr-innerContainer,.flatpickr-rContainer,.dayContainer {
            width: 220px;
        }
        .dayContainer {
            min-width: 220px;
        }
        .flatpickr-day {
            width: fit-content;
            max-width: 30px;
            height: 32px;
            line-height: 36px;
            padding: 0 .1em;
        }
        .checkbox input:checked ~ label:after {
            border-color: #495057;
        }

        .card-header {
            position: sticky;
            top: 68px;
            width: 100% !important;
            z-index: 1;
        }
        .table-connections thead th {
            border: 0;
        }
        #screen {
            font-family: Calibri,Arial, Helvetica, sans-serif;
            letter-spacing: 3px;
        }
        #screen:focus {
            background-color: #c8f5de;
        }
        .print-view-table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 0px;
            border-spacing: 0;
            border-collapse: collapse;
            background-color: transparent;
        }
        .print-view-table thead  {
            text-align: left;
            display: table-header-group;
            vertical-align: middle;
        }
        .print-view-table th  {
            border-top: 1px solid #DFE3E7;
            border-bottom: 1px solid #DFE3E7;
            padding: 1rem 0.6rem;
            font-family: "Rubik", Helvetica, Arial, serif;
            font-weight: bold;
            color: #475F7B;
            font-size:10px;
            text-align: center;
            text-transform: uppercase;
        }
        .print-view-table td  {
            border-top: 1px solid #DFE3E7;
            border-bottom: 1px solid #DFE3E7;
            padding: 6px;
            font-family: "Rubik", Helvetica, Arial, serif;
            color: #475F7B;
            font-size: 10px;
            text-align: center;
        }
        .table-charged-identifier {
            text-transform: uppercase;
        }

        .d-none {
            display: none;
        }

        [x-cloak] { display: none !important; }

        .customer-connections tr:first-child td {
            border-top: 1px solid #DFE3E7;
        }

        .existing-table{
            margin-top: -50px;
        }
        .existing-table thead{
            opacity: 0;
        }

    </style>

    <livewire:modals/>
@endsection
<div>
    <div class="row" style="margin-top: -5px;">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0" style="background-color: #FFFFFF;left: inherit;text-shadow: 1px 1px 2px #7DA0B1">
                    <div class="row d-flex align-items-center">
                        <div class="col-auto">
                            <h3>{{ __('locale.Activity Overview') }}</h3>
                            <div id="customer_name_title" style="{{ isset($customer) ? '' : 'visibility: hidden' }};height: 23px;" >
                                @if(isset($customer))
                                    <h5>
                                        <strong>{{ $customer->customer_name }}</strong>
                                    </h5>
                                @endif
                            </div>
                        </div>
                        <div class="col d-flex justify-content-end align-items-center ml-auto" >

                            <div class="custom-control custom-switch custom-switch-shadow custom-control-inline"
                                 @if(!isset($customer)) title="{{ __('locale.Please select a customer from your list first') }}" @endif>
                                @role('Admin')
                                <input type="checkbox" wire:model="printView"
                                       class="custom-control-input" {{ isset($customer) ? '' : 'disabled' }}
                                       id="customSwitchPrintView">
                                <label class="custom-control-label" style="margin-right: 2px;" for="customSwitchPrintView">
                                </label>

                                <span id="span-print-view" wire:loading.class="invisible" wire:target="printView"
                                      style="{{ isset($customer) && count($connectionsIncome) ? 'color: #475F7B' : 'color: #7DA0B1' }};font-size: 12px;">{{ __('locale.Print View') }}</span>
                                <div wire:loading.flex wire:target="printView" style="position: absolute;">
                                    <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="margin-left: 60px;color: #495057;"></x-loading.ball-spin-clockwise>
                                </div>
                                @endrole
                            </div>
                            @if(!$counter)
                                <div wire:ignore.self>
                                    <button style="margin-right: 3px;padding: 0.22rem 0.8rem;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 12px;color: #7DA0B1;text-align: center;" {{ $printView ? 'disabled' : '' }}
                                    title="@if(isset($customer) && !$printView) {{ __('locale.Launch') }} @elseif(isset($customer) && $printView) {{ __('locale.Please close the print view first') }}
                                    @else {{__('locale.Please select a customer from your list first')}} @endif"
                                            class="btn btn-sm btn-outline-secondary {{ session()->has('recovery_connection_process') ? 'd-none' : '' }}" wire:click="startChronos" id="chrono_actions_button">
                                        <div class="d-flex justify-content-center align-items-center" wire:key="launch-button-chronos-{{ time() }}">
                                            <span><i class="bx bx-play-circle" style="color: darkgreen;margin-right: 3px;"></i></span>
                                            <span wire:loading.remove wire:target="startChronos"><strong>{{ __('locale.Launch') }}</strong></span>
                                            <div wire:loading wire:target="startChronos" style="">
                                                <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm la-dark" style="margin-left: 20px;"></x-loading.ball-spin-clockwise>
                                            </div>
                                        </div>
                                    </button>
                                    <button style="margin-right: 3px;padding: 0.22rem 0.8rem;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 12px;color: #7DA0B1;text-align: center;" {{ !isset($customer) || !count($connectionsIncome) || $printView ? 'disabled' : '' }}
                                            class="btn btn-sm btn-outline-secondary mr-1 {{ session()->has('recovery_connection_process') ? '' : 'd-none' }}" id="chrono_loading_manual_modal_button">
                                        <div class="d-flex justify-content-center align-items-center" wire:key="chrono_loading_manual_modal_button-{{ time() }}">
                                            <span><i class="bx bx-stop-circle" style="color: red;margin-right: 3px;"></i></span>
                                            <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm la-dark" style="margin-left: 20px;"></x-loading.ball-spin-clockwise>
                                        </div>
                                    </button>
                                </div>
                            @else
                                <div wire:ignore.self>
                                    <button style="margin-right: 3px;padding: 0.22rem 0.8rem;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 12px;color: #7DA0B1;text-align: center;" {{ !isset($customer) || !count($connectionsIncome) || $printView ? 'disabled' : '' }}
                                        title="@if(isset($customer) && !$printView) {{ __('locale.Stop') }} @elseif(isset($customer) && $printView) {{ __('locale.Please close the print view first') }}
                                        @else {{__('locale.Please select a customer from your list first')}} @endif"
                                            class="btn btn-sm btn-light-secondary" wire:click="stopChronos" id="chrono_actions_button">
                                        <div class="d-flex justify-content-center align-items-center" wire:key="stop-button-chronos-{{ time() }}">
                                            <span><i class="bx bx-stop-circle" style="color: red;margin-right: 3px;"></i></span>
                                            <span wire:loading.remove wire:target="stopChronos"><strong>{{ __('locale.Stop') }}</strong></span>
                                            <div wire:loading wire:target="stopChronos" style="">
                                                <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm la-dark" style="margin-left: 20px;"></x-loading.ball-spin-clockwise>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            @endif
                            <button style="margin-right: 3px;padding: 0.22rem 0.8rem;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 12px;color: #7DA0B1;text-align: center;" {{ $printView ? 'disabled' : '' }}
                                title="@if(isset($customer) && !$printView) {{ __('locale.Add a new manual activity') }} @elseif(isset($customer) && $printView) {{ __('locale.Please close the print view first') }}
                                @else {{__('locale.Please select a customer from your list first')}} @endif"
                                    wire:click="$emit('showModal', 'tenant.activity-form-component', 'manual-activity', '{{ json_encode(['item'=>null,'customer'=>$customer]) }}')" class="btn btn-sm btn-outline-secondary"
                                    @if($printView) disabled title="{{ __('locale.Please close the print view first') }}" @endif>
                                <i wire:loading.remove wire:target="openManualActivityModal" class="bx bx-plus"></i>
                                <div wire:loading wire:target="openManualActivityModal">
                                    <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: lightblue;"></x-loading.ball-spin-clockwise>
                                </div>
                            </button>

                            @role('Admin')
                            <button style="margin-right: 3px;margin-left: 3px;padding: 0.38rem 0.8rem;;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 12px;color: #7DA0B1;text-align: center;"
                                    {{ !isset($customer) ? 'disabled' : '' }} wire:click="generatePDF('export')" class="btn btn-sm {{ !isset($customer) ? 'btn-light-secondary' : 'btn-outline-secondary' }} text-nowrap"
                                    title="@if(!isset($customer)) {{__('locale.Please select a customer from your list first')}} @endif">

                                <div class="d-flex justify-content-center">
                                    <div class="align-content-center" style="margin-right: 3px;">
                                        <img src="{{ asset('images/icon/icon-pdf.png') }}" width="15" height="15"  alt="pdf-icon">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span wire:loading.class="invisible" wire:target="generatePDF('export')">{{ __('locale.Print Protocol') }}</span> {{----}}
                                        <div wire:loading wire:target="generatePDF('export')" style="position: absolute;">
                                            <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="margin-left: 20px;"></x-loading.ball-spin-clockwise>
                                        </div>
                                    </div>
                                </div>
                            </button>
                            @endrole

                            <button {{ !isset($customer) || $printView ? 'disabled' : '' }} onclick="window.location='{{ route('customers.show',['customer' => $customer]) }}'"
                                    class="btn btn-sm {{ !isset($customer) || $printView ? 'btn-light-secondary' : 'btn-outline-secondary' }}"
                                    title="@if(isset($customer) && !$printView) {{ __('locale.Edit Customer') }}
                                    @elseif(isset($customer) && $printView) {{ __('locale.Please close the print view first') }}
                                    @else {{__('locale.Please select a customer from your list first')}} @endif"
                                    style="margin-right: 3px;cursor: {{ !isset($customer) ? '' : 'pointer' }}">
                                <div class="justify-content-center">
                                    <div class="align-content-center">
                                        <i class="bx bxs-map" style="font-size: 15px;"></i>
                                    </div>
                                </div>
                            </button>
                            <input {{ $printView ? 'disabled' : '' }}
                                   title="@if($printView) {{ __('locale.Please close the print view first') }} @endif"
                                   style="width: 210px;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 12px;color: #7DA0B1;margin-right: 3px;" type="search" class="form-control form-control-sm search-input" wire:model="searchTerm" placeholder="{{ __('locale.Search...') }}">
                        </div>
                        <div class="col d-flex justify-content-end align-items-center ml-auto" >
                            <input style="width: fit-content;width: -moz-fit-content;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 12px;color: #7DA0B1;text-align: center;" class="form-control form-control-sm" value="{{ __('locale.Total Duration') }}: {{ $customer ? $totalDuration : '---' }}" readonly>
                            <input style="width: fit-content;width: -moz-fit-content;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 12px;color: #7DA0B1;text-align: center;margin-left: 3px;" class="form-control form-control-sm" value="{{ __('locale.Total Units') }}: {{ $customer ? $totalUnits : '---' }}" readonly>
                            <input style="width: fit-content;width: -moz-fit-content;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 12px;color: #7DA0B1;text-align: center;margin-left: 3px;" class="form-control form-control-sm" value="{{ __('locale.Amount') }}: {{ $customer ? $totalAmount.' €' : '---' }}" readonly>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col d-flex display-block align-items-end ml-1" style="padding: 0 5px;max-width: 73px;">
                            <label for="bulk" style="font-size: 8px;visibility: hidden;">{{ __('locale.User') }}</label>
                            <select style="max-width: 100%;height: 55%;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 10px;color:{{ $printView ? 'white !important' : '#7DA0B1'}};@if($printView) background-color: #B0BED9; @endif"
                                    id="bulk" class="custom-select custom-select-sm" wire:model="bulk"
                                    @if($printView) disabled title="{{ __('locale.Please close the print view first') }}" @endif>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="300">300</option>
{{--                                <option value="500">500</option>--}}
{{--                                <option value="1000">1000</option>--}}
                                <option value="100000000">{{ __('locale.All') }}</option>
                            </select>
                            <input id="isLoadMoreHiddenInput" type="hidden"  value="{{ $isLoadMore }}" wire:key="isLoadMoreHiddenInput-{{ $isLoadMore }}">
                            <input id="hasMorePageHiddenInput" type="hidden"  value="{{ $hasMorePage }}" wire:key="hasMorePageHiddenInput-{{ $hasMorePage }}">
                        </div>
                        <div class="col d-flex display-block align-items-end" style="padding: 0 5px;" wire:ignore.self wire:key="'select-customer-overview'">
                            <label for="customers" class="text-nowrap" style="font-size: 10px;">{{ __('locale.Customer') }}</label>
                            <select style="height: 55%;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 10px;color: #7DA0B1;" id="customers"
                                    class="custom-select custom-select-sm" wire:model="selectedCustomer">
                                <option value="">{{ __('locale.All') }}</option>
                                @foreach($customers as $key => $value)
                                    <option class="option-chars-limited" value="{{ $key }}"><p>{{ ucwords(strtolower($value)) }}</p></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col d-flex display-block align-items-end" style="padding: 0 5px;" wire:ignore.self wire:key="'select-user-connections-overview'">
                            <label for="users" class="text-nowrap" style="font-size: 10px;">{{ __('locale.User') }}</label>
                            <select style="height: 55%;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 10px;color: {{ $printView ? 'white !important' : '#7DA0B1'}};@if($printView) background-color: #B0BED9; @endif" id="users"
                                    class="custom-select custom-select-sm" wire:model="selectedUser"
                                    @if($printView) disabled title="{{ __('locale.Please close the print view first') }}" @endif>
                                <option value="">{{ __('locale.All') }}</option>
                                @foreach($users as $value => $key)
                                    <option value="{{ $key }}"><p>{{ $value }}</p></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col d-flex display-block align-items-end" style="padding: 0 5px;" wire:ignore.self>
                            <label for="devices" class="text-nowrap" style="font-size: 10px;" >{{ __('locale.Device') }}</label>
                            <select style="height: 55%;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 10px;color: {{ $printView ? 'white !important' : '#7DA0B1'}};@if($printView) background-color: #B0BED9; @endif" id="devices" class="custom-select custom-select-sm" wire:model="selectedDevice"
                                    @if($printView) disabled title="{{ __('locale.Please close the print view first') }}" @endif>
                                <option value="">{{ __('locale.All') }}</option>
                                @foreach($devices as $key => $value)
                                    <option class="option-chars-limited" value="{{ $key }}">{{ ucwords(strtolower($value)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col d-flex display-block align-items-end" style="padding: 0 5px;" wire:ignore.self>
                            <label for="contact_type" class="text-nowrap" style="font-size: 10px;">{{ __('locale.Contact Type') }}</label>
                            <select style="height: 55%;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 10px;color: {{ $printView ? 'white !important' : '#7DA0B1'}};@if($printView) background-color: #B0BED9; @endif" id="contact_type" class="custom-select custom-select-sm" wire:model="selectedContactType"
                                    @if($printView) disabled title="{{ __('locale.Please close the print view first') }}" @endif>
                                <option value="">{{ __('locale.All') }}</option>
                                <option value="1">{{ __('locale.Email') }}</option>
                                <option value="2">{{ __('locale.Phone') }}</option>
                                <option value="3">{{ __('locale.Video Call') }}</option>
                                <option value="4">{{ __('locale.On Site') }}</option>
                                <option value="5">{{ __('VPN') }}</option>
                                <option value="6">{{ __('Teamviewer') }}</option>
                            </select>
                        </div>
                        <div class="col d-flex display-block align-items-end" style="padding: 0 5px;" wire:ignore.self>
                            <label for="bill" class="text-nowrap" style="font-size: 10px;">{{ __('locale.Status') }}</label>
                            <select style="height: 55%;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 10px;color: {{ $printView ? 'white !important' : '#7DA0B1'}};@if($printView) background-color: #B0BED9; @endif" id="bill" class="custom-select custom-select-sm" wire:model="selectedStatus"
                                    @if($printView) disabled title="{{ __('locale.Please close the print view first') }}" @endif>
                                <option value="">{{ __('locale.All') }}</option>
                                <option value="1">{{ __('locale.Charged') }}</option>
                                <option value="2">{{ __('locale.Not Charged') }}</option>
                                <option value="3">{{ __('locale.Hidden') }}</option>
                                <option value="4">{{ __('locale.Booked') }}</option>
                                <option value="5">{{ __('locale.Time Overlapping') }}</option>
                                <option value="6">{{ __('locale.Tariff Overlapping') }}</option>
                                <option value="7">{{ __('locale.Tariff Overlapping') }}</option>
                            </select>
                        </div>

                        @role('Admin')
                        <div class="col d-flex display-block align-items-end" style="padding: 0 5px;" wire:ignore.self>
                            <label for="tariffs" class="text-nowrap" style="font-size: 10px;">{{ __('locale.Tariff') }}</label>
                            <select style="height: 55%;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 10px;color: {{ $printView ? 'white !important' : '#7DA0B1'}};@if($printView) background-color: #B0BED9; @endif" id="tariffs" class="custom-select custom-select-sm" wire:model="selectedTariff"
                                    @if($printView) disabled title="{{ __('locale.Please close the print view first') }}" @endif>
                                <option value="">{{ __('locale.All') }}</option>
                                @foreach($tariffs as $item)
                                    <option class="option-chars-limited" value="{{ $item['id'] }}"><p>{{ ucwords(strtolower($item['tariff_name'])) }}</p></option>
                                @endforeach
                            </select>
                        </div>
                        @endrole

                        <div class="col d-flex display-block align-items-end" style="padding: 0 5px;" wire:ignore.self>
                            <label for="calendar-dropdown" class="text-nowrap" style="font-size: 10px;color: white;visibility: hidden;">{{ __('locale.Select') }}</label>
                            <select {{ $outCalendarRange ? 'disabled' : '' }} style="width: 100%;height: 55%;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 10px;color: #7DA0B1;"
                                    wire:change="selectCalendarOption($event.target.value)"
                                    id="calendar-dropdown" name="calendar-dropdown" class="custom-select custom-select-sm" wire:model="selectedCalendar">
                                <option value="">{{ __('locale.All') }}</option>
                                <option value="1">{{ __('locale.Current Month') }}</option>
                                <option value="2">{{ __('locale.Last Month') }}</option>
                                <option value="3">{{ __('locale.This Quarter') }}</option>
                                <option value="4">{{ __('locale.Last Quarter') }}</option>
                                <option value="5">{{ __('locale.This Year') }}</option>
                                <option value="6">{{ __('locale.Last Year') }}</option>
                            </select>
                        </div>
                        <div class="col-auto d-flex display-block justify-content-center align-items-end" style="padding: 0 5px 2px 5px;">
                            <label for="calendar-dropdown" style="font-size: 10px;color: white;visibility: hidden;">{{ __('locale.Select Tariff') }}</label>
                            <fieldset class="text-center"> {{-- style="width: 100px;"--}}
                                <div class="d-flex align-items-center" style="border-radius: 5px;border: 2px @if($errors->has('start_date')) #AA3333 @else @if($printView) #c8d2e4 @else #B0BED9 @endif @endif solid;">
                                    <div class="input-group-prepend">
                                        <i class='bx bx-calendar-check'></i>
                                    </div>
                                    <input style="width: 90px;height: 27px;font-size: 10px;text-align: center;padding-bottom: 0.33rem;color: #7DA0B1;"
                                           type="text" wire:model="start_date" class="custom-select custom-select-sm pickadate_start border-0 "
                                           placeholder="{{ __('locale.Start') }}" onchange="this.dispatchEvent(new InputEvent('input'))">
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-auto d-flex display-block justify-content-center align-items-end" style="padding: 0 5px 2px 5px;">
                            <label for="calendar-dropdown" style="font-size: 10px;color: white;visibility: hidden;">{{ __('locale.Select Tariff') }}</label>
                            <fieldset class="text-center">
                                <div class="d-flex align-items-center" style="border-radius: 5px;border: 2px @if($errors->has('start_date')) #AA3333 @else @if($printView) #c8d2e4 @else #B0BED9 @endif @endif solid;">
                                    <div class="input-group-prepend">
                                        <i class='bx bx-calendar-check'></i>
                                    </div>
                                    <input style="width: 90px;height: 27px;font-size: 10px;text-align: center;padding-bottom: 0.33rem;color: #7DA0B1;"
                                           type="text" wire:model="end_date" class="custom-select custom-select-sm pickadate_end border-0"
                                           placeholder="{{ __('locale.End') }}" onchange="this.dispatchEvent(new InputEvent('input'))">
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-auto d-flex justify-content-center align-items-center" style="padding: 20px 5px 0 5px;" wire:ignore.self>
                            <button wire:loading.class.remove="btn-outline-dark" wire:target="clearFilters" class="btn btn-sm btn-outline-dark btn-icon"
                                    style="border: 2px #B0BED9 solid;padding: 4px 5px;" wire:click="clearFilters" title="{{ __('locale.Clear') }}">
                                <i wire:loading.remove wire:target="clearFilters" class="bx bx-brush" style="margin-top: -2px;"></i>
                                <div wire:loading wire:target="clearFilters">
                                    <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-dark la-sm" style="width: 18px;height: 15px;"></x-loading.ball-spin-clockwise>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div wire:loading.delay wire:key="loading-spinner-for-component-properties" style="position: absolute;"
                         wire:target="bulk,selectedCustomer,selectedUser,selectedStatus,selectedDevice,selectedContactType,selectedTariff,selectedCalendar,start_date,end_date,
                                selectAllCheckboxes,checkboxSelectedAction,gotoPage">
                        <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="position: fixed;top: 240px;margin-left: 35px;color: #495057;"></x-loading.ball-spin-clockwise>
                    </div>
                    <div class="row pl-1 pr-1">
                        <table class="table table-hover table-connections mb-0" id="table-connections-header" style="">
                            <thead>
                            <tr>
                                <th class="actions-column p-0">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <fieldset class="row-checkbox-fieldset">
                                            <div class="checkbox">
                                                <input {{ auth()->user()->hasRole('Admin') ? '' : 'disabled' }} type="checkbox"
                                                       id="row-checkbox-connection-main" class="checkbox-input" style="border-color: #495057;"
                                                       {{$selectAllCheckboxes ? 'checked' : ''}} wire:click="selectAllCheckboxes">
                                                <label for="row-checkbox-connection-main"></label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </th>
                                <th class="p-0"></th>
                                @foreach($headers as $key => $value)
                                    @if(auth()->user()->hasRole('Admin'))
                                        <th class="sorting_{{$sortDirection}}-{{$key}} text-nowrap
                                        {{ $key == 'devicename' || $key == 'groupname' || $key == 'username' ? '' : 'text-center' }}"
                                            @if($key == 'actions') colspan="3" @endif style="font-size: 10px;@if($key === 'actions') padding: 1.5rem 1rem; @else padding: 1.5rem 0.6rem; @endif @if($key !== 'duration' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') cursor: pointer; @endif"
                                            @if($key !== 'duration' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') wire:click="sort('{{ $key }}')" @endif>
                                            @if($sortColumn == $key)
                                                <span wire:key="sort-key-{{$key}}">{!! $sortDirection == 'asc' ? '&#8679' : '&#8681' !!}</span>
                                            @endif
                                            <span style="letter-spacing: -1px;">{{ __('locale.'.$value) }}</span>
                                        </th>
                                    @else
                                        <th class="sorting_{{$sortDirection}}-{{$key}} text-nowrap
                                            {{ $key == 'devicename' || $key == 'groupname' || $key == 'username' || $key == 'notes' ? '' : 'text-center' }}"
                                            @if($key == 'actions') colspan="3" @endif style="font-size: 10px;
                                            @if($key === 'actions') padding: 1.5rem 1rem; @else padding: 1.5rem 1rem; @endif
                                            @if($key !== 'duration' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') cursor: pointer; @endif"
                                            @if($key !== 'duration' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') wire:click="sort('{{ $key }}')" @endif>
                                            @if($sortColumn == $key)
                                                <span wire:key="sort-key-{{$key}}">{!! $sortDirection == 'asc' ? '&#8679' : '&#8681' !!}</span>
                                            @endif
                                            <span style="letter-spacing: -1px;">{{ __('locale.'.$value) }}</span>
                                        </th>
                                    @endif
                                @endforeach
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        @if(!$printView)
                            <table class="table table-hover table-connections existing-table customer-connections-common" id="table-connections-header-hidden" wire:key="{{'main-table'.time()}}">
                                <thead>
                                    <tr>
                                        <th class="p-0"></th>
                                        <th class="p-0"></th>
                                        @foreach($headers as $key => $value)
                                            @if(auth()->user()->hasRole('Admin'))
                                                <th class="sorting_{{$sortDirection}}-{{$key}} text-nowrap {{ $key == 'devicename' || $key == 'groupname' || $key == 'username' ? '' : 'text-center' }}" @if($key == 'actions') colspan="3" @endif style="font-size: 10px;@if($key === 'actions') padding: 1.5rem 1rem; @else padding: 1.5rem 0.6rem; @endif @if($key !== 'duration' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') cursor: pointer; @endif" @if($key !== 'duration' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') wire:click="sort('{{ $key }}')" @endif>
                                                    @if($sortColumn == $key)
                                                        <span wire:key="sort-key-{{$key}}">{!! $sortDirection == 'asc' ? '&#8679' : '&#8681' !!}</span>
                                                    @endif
                                                    <span style="letter-spacing: -1px;">{{ __('locale.'.$value) }}</span>
                                                </th>
                                            @else
                                                <th class="sorting_{{$sortDirection}}-{{$key}} text-nowrap
                                                    {{ $key == 'groupname' || $key == 'username'  ? '' : 'text-center' }}"
                                                    @if($key == 'actions') colspan="3" @endif
                                                    style="font-size: 10px;
                                                    @if($key === 'actions') padding: 1.5rem 1rem; @else padding: 1.5rem 1rem; @endif
                                                    @if($key !== 'duration' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') cursor: pointer; @endif"
                                                    @if($key !== 'duration' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') wire:click="sort('{{ $key }}')" @endif>
                                                    @if($sortColumn == $key)
                                                        <span wire:key="sort-key-{{$key}}">{!! $sortDirection == 'asc' ? '&#8679' : '&#8681' !!}</span>
                                                    @endif
                                                    <span style="letter-spacing: -1px;">{{ __('locale.'.$value) }}</span>
                                                </th>
                                            @endif
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($connections as $item)
                                        @php
                                            $activityType = $item->isTV == true ? 'edit-connection' : 'manual-activity';
                                            $itemHasBorderLineEmergency = $item->overlaps_tariff == true && $item->is_tariff_overlap_confirmed == false;
                                            if($selectedStatus == 5){
                                                $itemHasBorderLineEmergency = false;
                                            }
                                        @endphp
                                        <tr style="@if($itemHasBorderLineEmergency) background-color: hsl(64, 76%, 85%); @elseif($item->overlaps_user) background-color: hsl(356, 76%, 95%); @endif"> {{--hsla(138, 62%, 89%, 1)--}}
                                            <td class="text-center pl-0 pr-0">
                                                <div x-data class="div-row-checkbox-fieldset-{{ $item->id }}" wire:ignore.self>
                                                    <fieldset class="row-checkbox-fieldset">
                                                        <div class="checkbox">
                                                            <input {{ auth()->user()->hasRole('Admin') ? '' : 'disabled' }} type="checkbox"
                                                                   id="row-checkbox-connection-{{ $item->id }}" class="checkbox-input" style="border-color: #495057;"
                                                                   @if(array_key_exists($item->id,$selectedRowCheckbox) || array_key_exists($item->id,$unSelectedRowCheckbox)) checked
                                                                   @endif
                                                                   wire:click="checkboxSelected('{{ $item->id }}')" value="{{ $item->id }}">
                                                            <label for="row-checkbox-connection-{{ $item->id }}"></label>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="row-checkbox-connection-invisible-{{ $item->id }} d-none">
                                                    <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
                                                </div>
                                            </td>
{{--                                            <td class="pl-0 pr-0">--}}
{{--                                                <i wire:click="redirectBorderLineComponent('{{ $item->id }}')" class="bx bx-show-alt" style="font-size: 10px; color: red;@if(!$this->borderlineCheck($item->id)) display: none; @else cursor: pointer; @endif" title="{{ __('locale.Border Line Emergence') }}"></i>--}}
{{--                                            </td>--}}
                                            <td class="p-0" wire:click="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')">
                                                <div class="row-image-connection-visible-{{ $item->id }}">
                                                    @if($item->isTV)
                                                        <img data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Teamviewer') }}</span>" width="25" height="25" src="{{ mix('assets/images/ico/icon_anydesk_64.png') }}" alt="tv-icon" />
                                                    @else
                                                        @if($item->contact_type == 1)
                                                            <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Email') }}</span>" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg>
                                                        @elseif($item->contact_type == 2)
                                                            <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Phone Call') }}</span>" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                                        @elseif($item->contact_type == 3)
                                                            <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Video Call') }}</span>" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                                                        @elseif($item->contact_type == 4)
                                                            <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.On Site') }}</span>" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                        @elseif($item->contact_type == 5)
                                                            <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.VPN') }}</span>" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield">
                                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                                            </svg>
                                                        @else
                                                            <i class="bx bx-plus"></i>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="row-image-connection-invisible-{{ $item->id }} d-none">
                                                    <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
                                                </div>
                                            </td>
                                            <td wire:click="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')" class="text-center" style="padding: 3px;">
                                                <strong>{{ strtoupper(__('locale.'.substr($item->charsForStartDate(),0,2))) }}</strong> - {{ $item->start_date->setTimezone(config('site.default_timezone'))->format('d.m.Y') }} @if($item->overlaps_color) <strong>{{ $item->start_date->setTimezone(config('site.default_timezone'))->format('H:i') }}</strong> @else {{ $item->start_date->setTimezone(config('site.default_timezone'))->format('H:i') }} @endif
                                            </td>
                                            <td wire:click="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')" class="text-center">
                                                <strong>{{ strtoupper(__('locale.'.substr($item->charsForEndDate(),0,2))) }}</strong> - {{ $item->end_date->setTimezone(config('site.default_timezone'))->format('d.m.Y') }} @if($item->overlaps_color) <strong>{{ $item->end_date->setTimezone(config('site.default_timezone'))->format('H:i') }}</strong> @else {{ $item->end_date->setTimezone(config('site.default_timezone'))->format('H:i') }} @endif
                                            </td>

                                            <td wire:click="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')" class="text-center">{{ $item->duration() }}m</td>

                                            @role('Admin')
                                            <td wire:click="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')" class="text-center">{{ $item->calculateUnit() ?? '-' }}</td>
                                            <td wire:click="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')" class="text-center text-nowrap">{{ $item->price ? $item->price.' €' : '-' }}</td>
                                            @endrole

{{--                                            @if(!$customer)--}}
                                                <td wire:click="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')">{{ $item->groupname }}</td>
{{--                                            @endif--}}

                                            <td class="text-wrap" wire:click="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')">{{ $item->devicename }}</td>
                                            <td wire:click="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')">{{ $item->username }}</td>
                                            <td class="text-center bill-column" wire:key="-loading-billing_state-{{ $item->id }}-{{ time() }}">
                                                <fieldset class="bill-checkbox-fieldset">
                                                    <div class="checkbox" wire:loading.remove wire:target="changeBillColumn('{{ $item->id }}')">
                                                        <input {{ $item->trashed() ? 'disabled' : '' }} type="checkbox" wire:click="changeBillColumn('{{ $item->id }}')" id="checkbox-bill-{{ $item->id }}" class="checkbox-input"
                                                               style="border-color: #495057;" {{ $item->billing_state == 'Bill' ? 'checked' : '' }}>
                                                        <label for="checkbox-bill-{{ $item->id }}"></label>
                                                    </div>
                                                    <div wire:loading wire:target="changeBillColumn('{{ $item->id }}')">
                                                        <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
                                                    </div>
                                                </fieldset>
                                            </td>

                                            @role('Admin')
                                            @if(!$customer)
                                            <td wire:click="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')" width="10%" class="text-center">
                                                @if($item->tariff()->exists())
                                                    <span class="bullet bullet-sm bullet-primary" style="width: 5px;height: 5px;background-color: {{ $item->tariff->color }};"></span>
                                                    {{ $item->tariff()->first()['tariff_name'] }}
                                                @elseif(!count($item->matchingTariff($item->start_date)))
                                                    <div class="badge badge-light-danger tariff-conflicts-badge">Tariff Conflicts</div>
                                                @endif
                                            </td>
                                            @endif
                                            @endrole

                                            <td wire:click="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')" width="12%" style="width: 10%;">{{ $item->narrowStringLenghtForNotesColumn() }}</td>
                                            <td class="actions-column custom-action-all-td" colspan="3" style="padding: 0.2rem 0;" wire:key="-loading-action_buttons-{{ $item->id }}-{{ time() }}">
                                                @if($loop->first)
                                                    <div class="d-flex justify-content-center align-items-center custom-action-all">
                                                        <div class="btn-group dropleft dropdown pt-2">
                                                            <button type="button" class="btn btn-sm btn-icon button-row-conn-loading-state" id="dropdownMenuOffset"
                                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="5,20">
                                                                <i id="dots-icon-for-buttons-global" class="bx bx-dots-horizontal-rounded"></i> {{--wire:loading.remove wire:target="openOverlapsModal('{{ $item->id }}'),openActivityModal('{{ $item->id }}')"--}}
                                                                <div class="div-button-loading-state-global d-none" style="color: black;width: 100%;height: 100%;">
                                                                    <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
                                                                </div>
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset" style="width: 20px;">
                                                                <button class="dropdown-item" wire:click="getTickedCheckboxes">
                                                                    <span>{{__('locale.Printed')}}</span>
                                                                </button>
                                                                <button class="dropdown-item">
                                                                    <span>{{__('locale.Booked')}}</span>
                                                                </button>
                                                                <button class="dropdown-item">
                                                                    {{ __('locale.Hidden') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                    <div class="d-flex justify-content-center align-items-center">
                                                    <div class="btn-group dropleft dropdown">
                                                        <button type="button" class="btn btn-sm btn-icon button-row-conn-loading-state" id="dropdownMenuOffset"
                                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="5,20">
                                                            <i id="dots-icon-for-buttons-{{ $item->id }}" class="bx bx-dots-horizontal-rounded"></i> {{--wire:loading.remove wire:target="openOverlapsModal('{{ $item->id }}'),openActivityModal('{{ $item->id }}')"--}}
                                                            <div class="div-button-loading-state-{{ $item->id }} d-none" style="color: black;width: 100%;height: 100%;">
                                                                <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
                                                            </div>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset" style="width: 20px;">
                                                            <button {{ !$item->overlaps_color ? 'disabled' : '' }} class="dropdown-item" wire:click.prevent="openOverlapsModal('{{ $item->id }}')">
                                                                <span style="@if(!$item->overlaps_color) color: lightgrey; @endif">{{__('locale.Overlaps')}}</span>
                                                            </button>
                                                            <button {{ !$itemHasBorderLineEmergency ? 'disabled' : '' }} class="dropdown-item" wire:click.prevent="splittingConnection('{{ $item->id }}')">
                                                                <span style="@if(!$itemHasBorderLineEmergency) color: lightgrey; @endif">{{__('locale.Splitting')}}</span>
                                                            </button>
                                                            <button {{ !$itemHasBorderLineEmergency ? 'disabled' : '' }} class="dropdown-item" wire:click.prevent="$emit('showModal', 'tenant.confirm-component', '{{ $item->id }}')">
                                                                <span style="@if(!$itemHasBorderLineEmergency) color: lightgrey; @endif">{{__('locale.Select tariff')}}</span>
                                                            </button>
                                                            <button class="dropdown-item" wire:click.prevent="$emit('showModal', 'tenant.activity-form-component', '{{$activityType}}', '{{ json_encode(['item'=>$item->id,'customer'=>$customer]) }}')">
                                                                {{ __('locale.Edit') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @if($item->trashed())
                                                        <span title="{{ __('locale.Connection Hidden') }}">
                                                            <i class="bx bx-hide" style="font-size: 10px;color: red;"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        @if( $searchTerm == '')
                                            <td colspan="12" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There is no connections') }}</p></td>
                                        @else
                                            <td colspan="12" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There is no connections that matches') }} "{{ $searchTerm }}"</p></td>
                                        @endif
                                    @endforelse
                                </tbody>
                            </table>

                            @livewire('tenant.customer-connections-load-more-component',
                            [
                                'pageNumber'=>$pageNumber,'bulk'=>$bulk,
                                'selectedCustomer'=>$selectedCustomer,'selectedUser'=>$selectedUser,
                                'selectedDevice'=>$selectedDevice,'selectedContactType'=>$selectedContactType,
                                'selectedStatus'=>$selectedStatus,'selectedTariff'=>$selectedTariff,
                                'sortColumn'=>$sortColumn,'sortDirection'=>$sortDirection,
                                'selectedCalendar'=>$selectedCalendar,
                                'start_date'=>$start_date,'end_date'=>$end_date,
                                'printView'=>$printView,'searchTerm'=>$searchTerm,
                                //'selectedRowCheckbox'=>$selectedRowCheckbox,'unSelectedRowCheckbox'=>$unSelectedRowCheckbox,
                            ],
                            key('customer-connections-load-more-component-'.time()))


                            <div>
                                @if(count($connections) && (int)$bulk <= 1000)
                                    {{ $connections->links() }}
                                @elseif((int)$bulk > 1000 && $hasMorePage)
                                    <tr>
                                        <td colspan="12">
                                            <div id="div-load-more-spinner" class="d-flex justify-content-center align-items-center invisible">
                                                <span style="color: #475F7B; font-size: 10px;font-weight: bold;">{{ strtoupper( __('locale.Loading More')) }}</span>
                                                <div style="margin-left: 5px;align-self: center;">
                                                    <div class="la-line-scale la-dark la-sm" style="width: 30px;">
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </div>
                        @else
                            <div class="pt-1">
                                @livewire('tenant.connection-print-view-component',['selectedCustomer' => $selectedCustomer, 'selectedCalendar' => $selectedCalendar, 'start_date' => $start_date, 'end_date' => $end_date],key($selectedCustomer .'-'. time()))
                            </div>
                        @endif

                        {{--Connection Splitting Modal--}}
                        <div class="modal fade" id="splittingModal" tabindex="-1" aria-labelledby="splittingModalLabel" aria-hidden="true"
                             data-backdrop="static" data-keyboard="false" wire:key="splitting-connection-modal">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    @livewire('tenant.splitting-component')
                                </div>
                            </div>
                        </div>

                          {{--Connection Overlaps Modal--}}
                        <div class="modal fade" id="overlapsModal" tabindex="-1" aria-labelledby="overlapsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    @livewire('tenant.connection-overlaps-component')
                                </div>
                            </div>
                        </div>

                        {{--Add manual activity--}}
                        <div class="modal fade" id="manualActivityModal" tabindex="-1" aria-labelledby="manualActivityModalLabel" aria-hidden="true"
                             data-backdrop="static" data-keyboard="false" wire:key="manual-activity-modal">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
{{--                                    @livewire('tenant.manual-activity-component')--}}
                                </div>
                            </div>
                        </div>

                            {{-- Add User Modal --}}
                            <div class="modal fade custom-modal-cstm" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true"
                                 data-backdrop="static" data-keyboard="false" wire:key="add-user-modal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
{{--                                        @livewire('tenant.user-modal-component',['customer' => $customer], key($customer ? 'user-modal-'.$customer->id : 'user-modal-123456'))--}}
                                    </div>
                                </div>
                            </div>

                            {{-- User Modal on edit connection --}}
                            <div class="modal fade custom-modal-cstm" id="addUserModalEditConnection" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true"
                                 data-backdrop="static" data-keyboard="false" wire:key="add-user-modal-edit-connection">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
{{--                                        @livewire('tenant.user-modal-component',['customer' => $customer,'actionType'=>'addUserModalEditConnection'], key($customer ? 'user-modal-edit-connection-'.$customer->id : 'user-modal-edit-connection-123456'))--}}
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('custom_scripts')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/de.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
{{--    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>--}}
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{asset('js/scripts/forms/select/form-select2.js')}}"></script>

    {{-- momentjs for time --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.sent', (message,component) => {
                if (message.updateQueue[0].method === 'openActivityModal' || message.updateQueue[0].method === 'openOverlapsModal'
                    || message.updateQueue[0].method === 'splittingConnection') {
                    $('.button-row-conn-loading-state').attr('disabled','disabled');
                    $('.bill-checkbox-fieldset').attr('disabled','disabled');
                    $('.row-checkbox-fieldset').attr('disabled','disabled');
                    $('.row-image-connection-visible-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                    $('.row-image-connection-invisible-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                    $('#dots-icon-for-buttons-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                    $('.div-button-loading-state-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                } else if(message.updateQueue[0].payload.method === 'checkboxSelected') {
                    $('.div-row-checkbox-fieldset-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                    $('.row-checkbox-connection-invisible-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                }
            });
            Livewire.hook('message.processed', (message, component) => {
                if (message.updateQueue[0].method === 'openActivityModal' || message.updateQueue[0].method === 'openOverlapsModal'
                    || message.updateQueue[0].method === 'splittingConnection') {
                    $('.button-row-conn-loading-state').removeAttr('disabled','disabled');
                    $('.bill-checkbox-fieldset').removeAttr('disabled','disabled');
                    $('.row-checkbox-fieldset').removeAttr('disabled','disabled');
                    $('.row-image-connection-visible-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                    $('.row-image-connection-invisible-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                    $('#dots-icon-for-buttons-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                    $('.div-button-loading-state-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                } else if(message.updateQueue[0].payload.method === 'checkboxSelected') {
                    $('.div-row-checkbox-fieldset-' + message.updateQueue[0].payload.params[0]).removeClass('d-none');
                    $('.row-checkbox-connection-invisible-' + message.updateQueue[0].payload.params[0]).addClass('d-none');
                } else if(message.updateQueue[0].payload.method === 'gotoPage') {
                    $(window).scrollTop(0);
                }
            });
            Livewire.hook('message.sent', (message,component) => {
                if (message.updateQueue[0].method === 'changeBillColumn') {
                    $('.button-row-conn-loading-state').attr('disabled','disabled');
                }
            });
            Livewire.hook('message.processed', (message, component) => {
                if (message.updateQueue[0].method === 'changeBillColumn') {
                    $('.button-row-conn-loading-state').removeAttr('disabled','disabled');
                }
            });
            Livewire.hook('message.sent', (message,component) => {
                if (message.updateQueue[0].payload.event === 'refresh') {
                    $('.button-row-conn-loading-state').attr('disabled','disabled');
                    $('.bill-checkbox-fieldset').attr('disabled','disabled');
                }
            });
            Livewire.hook('message.processed', (message, component) => {
                if (message.updateQueue[0].payload.event === 'refresh') {
                    $('.button-row-conn-loading-state').removeAttr('disabled','disabled');
                    $('.bill-checkbox-fieldset').removeAttr('disabled','disabled');
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
            Livewire.hook('message.received', (message,component) => {
                if (message.updateQueue[0].method === 'startChronos') {
                    window.livewire.emitTo('tenant.partials.timer-nav-database-component','startChronosTimerComponent');
                }
                // else if (message.updateQueue[0].payload.event === 'closeManualActivityModal') {
                //     console.log('close manual activity modal');
                //     window.livewire.emitTo('tenant.partials.timer-nav-database-component','componentUnmount');
                // }
            });
            Livewire.hook('message.sent', (message,component) => {
                if (message.updateQueue[0].payload.event === 'stopChronoValue' || message.updateQueue[0].payload.event === 'addManualActivity') {
                    $('#chrono_actions_button').addClass('d-none');
                    $('#chrono_loading_manual_modal_button').removeClass('d-none');

                    $('#loading-spinner-for-nav-timer-icon-button').addClass('d-none');
                    $('#loading-spinner-for-nav-timer-button').removeClass('d-none');
                }
            });
            Livewire.hook('message.processed', (message,component) => {
                if (message.updateQueue[0].payload.event === 'stopChronoValue' || message.updateQueue[0].payload.event === 'addManualActivity') {
                    $('#chrono_actions_button').removeClass('d-none');
                    $('#chrono_loading_manual_modal_button').addClass('d-none');

                    $('#loading-spinner-for-nav-timer-icon-button').removeClass('d-none');
                    $('#loading-spinner-for-nav-timer-button').addClass('d-none');
                }
            });
        });
        $(document).ready(function(){
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
                    livewire.emitTo('tenant.contact-modal-component','devicesSelect',$(this).val());
                });
            }
            window.livewire.on('loadContactDeviceSelect2',()=>{
                loadContactDeviceSelect2();
            });

            window.initManualActivityTimeMasks = () => {
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
                $('.manual_activity_time').mask(maskBehavior, spOptions);
                $("input[data-type='time']").mask(maskBehavior, spOptions);
            };
            initManualActivityTimeMasks();
            window.livewire.on('initManualActivityTimeMasks', () => {
                initManualActivityTimeMasks();
            });
            window.addEventListener('scrollTopTable',event => {
                $(window).scrollTop(0);
            });
            function loadContentPage() {
                var elementHeader = $('.card-header');
                var contentWidth = $('.card-content').width();
                elementHeader.css('width',contentWidth);

                var arrOfWidths=[], arrOfObjects=[],arrCalculates=[],
                    i=0;

                var lenght = $('#table-connections-header-hidden thead tr:first').children('th').length;
                let resTable = new Array(lenght);
                let resTablet = new Array(lenght);
                for (let j = 0; j < resTable.length; j++) {
                    resTable[j] = 0;
                }

                for (let j = 0; j < resTable.length; j++) {
                    resTablet[j] = 0;
                }

                cols = $('#table-connections-header-hidden thead tr').children('th');
                cols.each(function(index,elem) {
                    let mWid = $(elem).outerWidth();
                    resTablet[index] = mWid;
                });

                $('#table-connections-header th').each(function(index) {
                    $(this).css("min-width",resTablet[index]+"px");
                });
            }

            loadContentPage();
            window.livewire.on('loadContentPage',()=>{
                loadContentPage();
            });
            $(window).resize(function () {
                loadContentPage();
            });
            window.initDatePickerManualActivity = () => {
                if ($locale_sys === "en") {
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
                }
            };
            initDatePickerManualActivity();
            window.livewire.on('initDatePickerManualActivity',()=>{
                initDatePickerManualActivity();
            });
            Livewire.emit('redirectToastrMessages');
            Livewire.emit('stopChrono_redirected');
            Livewire.emit('recovery_connection_process');
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
                if ($locale_sys === "en") {
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
                }
            };
            initDatePicker();
            window.livewire.on('datePickerHydrate',()=>{
                initDatePicker();
            });
            toastr.options = {
                positionClass: 'toast-top-center',
                showDuration: 1000,
                timeOut: 3000,
                hideDuration: 2000,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut'
            }
            window.addEventListener('showToastrSuccess', event => {
                toastr.success('', event.detail.message).css("width","fit-content")
            });
            window.addEventListener('showToastrError', event => {
                toastr.error('',event.detail.message).css("width","fit-content")
            })

            $("#overlapsModal").on('hidden.bs.modal', function(){
                livewire.emit('closedOverlapsModal');
            });
            window.addEventListener('openOverlapsModal', event => {
                $('#overlapsModal').modal('show');
            });
            window.addEventListener('closedOverlapsModal', event => {
                $('#overlapsModal').modal('hide');
            });
            window.addEventListener('openManualActivityModal', event => {
                $('#manualActivityModal').modal('show');
            });

            $("#manualActivityModal").on('hidden.bs.modal', function(){
                window.livewire.emitTo('tenant.partials.timer-nav-database-component','componentUnmount');
            });
            window.addEventListener('openHideConnectionModal', event => {
                $('#connectionHideModal').modal('show');
            });
            window.addEventListener('closeHideConnectionModal', event => {
                $('#connectionHideModal').modal('hide');
            });

            window.addEventListener('openSplittingModal', event => {
                $('#splittingModal').modal('show');
            });
            window.addEventListener('closeSplittingModal', event => {
                $('#splittingModal').modal('hide');
            });

            window.addEventListener('openUserModal', event => {
                if (event.detail.activityName == 'addUserModalEditConnection') {
                    $('#addUserModalEditConnection').modal('show');
                } else {
                    $('#addUserModal').modal('show');
                    $('#manualActivityModal').modal('hide');
                }
            });

            window.addEventListener('closeUserModal', event => {
                if (event.detail.activityName == 'addUserModalEditConnection') {
                    $('#addUserModalEditConnection').modal('hide');
                } else {
                    $('#manualActivityModal').modal('show');
                    $('#addUserModal').modal('hide');
                }
            });

            window.addEventListener('setTimezone', event => {
                let timezzone = moment.tz.guess();
                if(!timezzone){
                    timezzone = 'Europe/Berlin';
                }
                livewire.emit('setTimezone', timezzone);
            });

        });

        let count = 0;
        let findEle = null;
        let isInputCheckEvent = false;
        window.onscroll = function (ev) {
            if ((window.innerHeight + window.scrollY) >= $(document).height() - 1000) {
                let isLoadMore = $('#isLoadMoreHiddenInput').val();
                let hasMorePage = $('#hasMorePageHiddenInput').val();
                if (count === 0 && isLoadMore && hasMorePage && (findEle == null || findEle.length == 0)) {
                    if ($(document).find('#load_more_connections_btn').length == 1) {
                        $('#loading-text-spinner').text('{{ __('locale.Loading More') }}');
                        $('#div-load-more-spinner').removeClass('invisible');
                        $(document).find('#load_more_connections_btn').trigger('click');
                        if($(document).find('.page-2').length == 0 && isInputCheckEvent){
                            $('#div-load-more-spinner').removeClass('invisible');
                            window.livewire.emitTo('tenant.customer-connections-load-more-component','loadMoreUpdatePage');
                        }
                    }
                    count++;
                }
            }
        };
        findEle = $(document).find('.customer-connections');

        window.addEventListener('loadMoreConnectionSuccess', event => {
            $('#div-load-more-spinner').addClass('invisible');
            count = 0;
            isInputCheckEvent = false;
        });

        window.addEventListener('loadMoreConnectionOnRefresh', event => {
            isInputCheckEvent = true;
        });
        window.addEventListener('updateTableDataWidth', event => {
            // fix td width for new table data
            var max = 0;
            var _max_width_td_row = null;
            let isLoadMore = $('#isLoadMoreHiddenInput').val();
            let hasMorePage = $('#hasMorePageHiddenInput').val();
            if (isLoadMore && hasMorePage) {
                $('#table-connections-header-hidden tbody tr td').each(function (index, elem) {
                    let current_td_width = $(this).width();
                    max = Math.max(current_td_width, max);
                    if (current_td_width >= max) {
                        _max_width_td_row = $(elem).parent();
                    }
                });
                if (_max_width_td_row) {
                    let max_width_tds = $(_max_width_td_row).children('td');
                    $('.customer-connections-common tbody tr:first').each(function (index, trElem) {
                        $($(trElem).children('td')).each(function (index, tdElem) {
                            $(tdElem).css("min-width", $(max_width_tds[index]).innerWidth() + "px")
                        });
                    });

                    $('.customer-connections-common tbody tr:last').each(function (index, trElem) {
                        $($(trElem).children('td')).each(function (index, tdElem) {
                            $(tdElem).css("min-width", $(max_width_tds[index]).innerWidth() + "px")
                        });
                    });
                }
            }
        });

    </script>
    <script>

        /*
        function myComponent(livewireComponent)
        {
            console.log('livewireComponent',livewireComponent);
            return {
                selectedConnections: livewireComponent.entangle('selectedRowCheckbox')
            }
        }
        */

        window.addEventListener('focusErrorInput', event => {
            var $field = '#' + event.detail.field;
            $($field).focus()
        })
    </script>
@endsection
