@php
    $isAdmin = auth()->user()->hasRole('Admin');
@endphp
<table class="table customer-connections-common" id="table-connections-header-hidden">
    <thead>
    <tr>
        <div class="filter-processing-loader" style="background:#ffffff;position:absolute;left:45%;z-index:10;display: none;">
            <div style="align-self: center; margin: 8px 8px 8px 16px;">
                <div class="la-line-scale la-dark la-sm" style="width: 30px;">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </tr>
    <tr>
        <th class="f-10px pl-0 pr-0">
            {{-- master checkbox for bulk action selection --}}
            <label for="row_checkbox_connection_main"></label>
            <input type="checkbox" class="form-check-input master-checkbox"
                   {{ $isAdmin ? '' : 'disabled' }}
                   id="row_checkbox_connection_main" {{!empty($selectAllCheckboxes) ? 'checked' : ''}}>
        </th>

        <th>
            {{-- bulk action option with dropdown --}}
            {{-- TODO:: 1. for bulk action on checkboxes --}}
            <div class="d-none justify-content-end align-items-center custom-action-all">
                <div class="btn-group dropleft dropdown pt-2">
                    <button type="button" class="btn btn-sm btn-icon button-row-conn-loading-state" id="dropdownMenuOffset"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="5,20">
                        <i id="dots-icon-for-buttons-global" class="bx bx-dots-horizontal-rounded"></i> {{--wire:loading.remove wire:target="openOverlapsModal('{{ $item->id }}'),openActivityModal('{{ $item->id }}')"--}}
                        <div class="div-button-loading-state-global d-none" style="color: black;width: 100%;height: 100%;">
                            <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
                        </div>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset"
                         style="width: 20px;">
                        <button class="dropdown-item bulk-action" data-action-type="printed">
                            <span>{{__('locale.Printed')}}</span>
                        </button>
                        <button class="dropdown-item bulk-action" data-action-type="booked">
                            <span>{{__('locale.Booked')}}</span>
                        </button>
                        <button class="dropdown-item bulk-action" data-action-type="hidden">
                            {{ __('locale.Hidden') }} / {{ __('locale.Revert') }}
                        </button>
                    </div>
                </div>
            </div>
        </th>

        {{-- dynamic headers --}}
        @if(!empty($headers))
            @foreach($headers as $key => $value)
                @if($isAdmin)
                    <th class="sorting_{{$sortDirection}}-{{$key}} text-nowrap {{$loop->last ? 'custom-action-all-th' : ''}}
                    {{ $key == 'devicename' || $key == 'groupname' || $key == 'username' ? '' : 'text-center' }}
                    {{($key !== 'status' && $key !== 'duration' && $key !== 'duration_with_interval' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') ? 'sortable_column' : '' }}"
                        @if($key == 'actions') colspan="3" @endif style="font-size: 10px; {{'padding: 0.7rem 0;'}} {{($key !== 'duration' && $key !== 'duration_with_interval' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') ? 'cursor: pointer;' : ''}}"
                        data-current-sort-direction="{{$sortDirection}}" data-sort-column="{{$key}}">
                        @if($sortColumn == $key)
                            <span>{!! $sortDirection == 'asc' ? '&#8679' : '&#8681' !!}</span>
                        @endif
                        <span style="letter-spacing: -1px;">{!! __('locale.'.$value, ['break' => '<br />']) !!}</span>
                    </th>
                @else
                    <th class="sorting_{{$sortDirection}}-{{$key}} text-nowrap {{$loop->last ? 'custom-action-all-th' : ''}}
                    {{ $key == 'devicename' || $key == 'groupname' || $key == 'username' || $key == 'notes' ? '' : 'text-center' }}
                    @if($key !== 'duration' && $key !== 'duration_with_interval' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') sortable_column @endif"
                        @if($key == 'actions') colspan="3" @endif style="font-size: 10px;
                    @if($key === 'actions') padding: 1.5rem 1rem; @else padding: 1.5rem 1rem; @endif
                    @if($key !== 'duration' && $key !== 'duration_with_interval' && $key !== 'actions' && $key !== 'units' && $key !== 'day' &&  $key !== 'tariff') cursor: pointer; @endif"
                        data-current-sort-direction="{{$sortDirection}}" data-sort-column="{{$key}}">
                        @if($sortColumn == $key)
                            <span wire:key="sort-key-{{$key}}">{!! $sortDirection == 'asc' ? '&#8679' : '&#8681' !!}</span>
                        @endif
                        <span style="letter-spacing: -1px;">{!! __('locale.'.$value, ['break' => '<br />']) !!}</span>
                    </th>
                @endif
            @endforeach
        @endif
    </tr>
    </thead>
    <tbody class="table-border-bottom-0">
    @forelse($connections as $item)
        @php
            $activityType = $item->isTV == true ? 'edit-connection' : 'manual-activity';
            $itemHasBorderLineEmergency = $item->overlaps_tariff == true && $item->is_tariff_overlap_confirmed == false;
            if($selectedStatus == 5){
                $itemHasBorderLineEmergency = false;
            }
        @endphp

        {{-- actual row --}}
        <tr class="{{ $loop->first ? 'first-tr' : ''}} @if($itemHasBorderLineEmergency) border-line-emergency @elseif($item->overlaps_user) overlaps-user @endif">

            {{-- slave checkbox for bulk action selection --}}
            <td class="pl-0 pr-0">
                <label for="row-checkbox-connection-{{ $item->id }}"></label>
                <input type="checkbox"
                       class="form-check-input select-row-checkbox master-checkbox form-check-input"
                       id="row-checkbox-connection-{{ $item->id }}" {{!empty($selectAllCheckboxes) ? 'checked' : ''}}
                       value="{{ $item->id }}"
                       name="selected_connections[]" {{ $isAdmin ? '' : 'disabled' }}>
            </td>

            {{-- connection type image --}}
            <td class="edit-connection-item text-center" data-connection="{{$item->id}}" data-connection-type="{{$activityType}}">
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
            </td>

            {{-- start date time --}}
            <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}" class="text-center edit-connection-item">
                <strong>{{ strtoupper(__('locale.'.substr($item->charsForStartDate(),0,2))) }}</strong>
                - {{ $item->start_date->setTimezone(config('site.default_timezone'))->format('d.m.Y') }}
                @if($item->overlaps_color)
                    <strong>{{ $item->start_date->setTimezone(config('site.default_timezone'))->format('H:i') }}</strong>
                @else
                    {{ $item->start_date->setTimezone(config('site.default_timezone'))->format('H:i') }}
                @endif
            </td>

            {{-- end date time --}}
            <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}"
                class="text-center edit-connection-item">
                <strong>{{ strtoupper(__('locale.'.substr($item->charsForEndDate(),0,2))) }}</strong>
                - {{ $item->end_date->setTimezone(config('site.default_timezone'))->format('d.m.Y') }}
                @if($item->overlaps_color)
                    <strong>{{ $item->end_date->setTimezone(config('site.default_timezone'))->format('H:i') }}</strong>
                @else
                    {{ $item->end_date->setTimezone(config('site.default_timezone'))->format('H:i') }}
                @endif
            </td>

            {{-- duration without interval --}}
            <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}"
                class="text-center edit-connection-item">{{ $item->getWithoutIntervalDuration() }}
            </td>

            {{-- duration with interval --}}
            <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}"
                class="text-center edit-connection-item with-tarif-interval">{{ $item->getWithIntervalDuration() }}
            </td>

            @if($isAdmin)
                {{-- units --}}
                <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}"
                    class="text-center edit-connection-item">{{ $item->calculateUnit() ?? '-' }}</td>
                {{-- price --}}
                <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}"
                    class="text-center text-nowrap edit-connection-item">{{ $item->price ? $item->price.' €' : '-' }}</td>
            @endif

            {{-- customer --}}
            <td class="edit-connection-item" data-connection="{{$item->id}}"
                data-connection-type="{{$activityType}}">{{ $item->groupname }}</td>

            {{-- device --}}
            <td class="text-wrap edit-connection-item font-gray" data-connection="{{$item->id}}"
                data-connection-type="{{$activityType}}">{{ $item->devicename }}</td>

            {{-- user --}}
            <td class="edit-connection-item" data-connection="{{$item->id}}"
                data-connection-type="{{$activityType}}">{{ $item->username }}</td>

            {{-- status --}}
            <td class="edit-connection-item text-center" data-connection="{{$item->id}}"
                data-connection-type="{{$activityType}}">
                @if($item->trashed())
                    <span title="{{ __('locale.Connection Hidden') }}" class="hiddenIcon">
                        <i widht="15" height="15" class="text-danger" data-feather="eye-off"></i>
                    </span>
                @elseif($item->booked)
                    <span title="{{ __('locale.Booked') }}" class="bookedIcon">
                        <i class="bx bx-calendar-check" style="font-size: 20px;color: #1a233a;"></i>
                    </span>
                @elseif($item->printed)
                    <span title="{{ __('locale.Printed') }}" class="printedIcon">
                        <i widht="15" height="15" class="text-dark" data-feather="printer"></i>
                    </span>
                @endif

                @if (!$item->isTariffAvailable())
                    <i class="tf-icons bx bxs-coin-stack text-danger" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true" data-bs-title="{{ __('locale.Please create a tariff for this time slot') }}"></i>
                @endif
            </td>

            {{-- charge --}}
            <td class="text-center bill-column">
                <div class="checkbox" id="change-bill-{{ $item->id }}">
                    <input {{ $item->trashed() ? 'disabled' : '' }} type="checkbox" data-connection="{{ $item->id }}" id="checkbox-bill-{{ $item->id }}"
                           class="checkbox-input change-bill-checkbox form-check-input" {{ $item->billing_state == 'Bill' ? 'checked' : '' }}>
                    <label for="checkbox-bill-{{ $item->id }}"></label>
                </div>
                <div class="d-none" id="change-bill-spinner-{{ $item->id }}" style="display: inline-flex;">
                    <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
                </div>
            </td>

            {{-- tariff --}}
            @if($isAdmin)
                <td class="text-center">
                    @if($item->tariff)
                        <span class="bullet bullet-sm bullet-primary"
                              style="width: 5px;height: 5px;background-color: {{ $item->tariff->color }};"></span>
                        {{ !empty($item->tariff->tariff_name) ? $item->tariff->tariff_name : '' }}
                    @elseif(!count($item->matchingTariff($item->start_date)))
                        <div class="badge badge-light-danger tariff-conflicts-badge">Tariff Conflicts</div>
                    @endif
                </td>
            @endif

            {{-- job description --}}
            <td class="job-description" data-bs-offset="0,4" data-bs-original-title="{{ $item->activity_report }}"
                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-secondary">
                {{ $item->narrowStringLenghtForActivityReportColumn() }}
            </td>

            {{-- actions --}}
            <td class="actions-column" colspan="3" style="padding: 0.2rem 0;" wire:key="-loading-action_buttons-{{ $item->id }}-{{ time() }}">
                <div class="dropdown d-flex justify-content-center align-items-center dropdownColumn">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" id="dropdownMenuOffset" aria-haspopup="true" aria-expanded="false" data-offset="5,20">
                        <i id="dots-icon-for-buttons-{{ $item->id }}" class="bx bx-dots-horizontal-rounded"></i>
                        <div class="div-button-loading-state-{{ $item->id }} d-none" style="color: black;width: 100%;height: 100%;">
                            <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
                        </div>
                    </button>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                        <button {{ !$item->overlaps_color ? 'disabled' : '' }} class="dropdown-item {{$item->overlaps_color ? 'time-overlapped-connection' : ''}}" data-connection="{{ $item->id }}">
                            <span style="@if(!$item->overlaps_color) color: lightgrey; @endif">{{__('locale.Overlaps')}}</span>
                        </button>
                        <button {{ !$itemHasBorderLineEmergency ? 'disabled' : '' }} class="dropdown-item {{ $itemHasBorderLineEmergency ? 'split-connection' : '' }}" data-connection="{{ $item->id }}">
                            <span style="@if(!$itemHasBorderLineEmergency) color: lightgrey; @endif">{{__('locale.Splitting')}}</span>
                        </button>
                        <button
                            {{ !$itemHasBorderLineEmergency ? 'disabled' : '' }} class="dropdown-item  {{ $itemHasBorderLineEmergency ? 'confirm-connection-tariff' : '' }}"
                            data-connection="{{ $item->id }}">
                            <span
                                style="@if(!$itemHasBorderLineEmergency) color: lightgrey; @endif">{{__('locale.Select tariff')}}</span>
                        </button>
                        <button class="dropdown-item edit-connection-item" data-connection="{{$item->id}}" data-connection-type="{{$activityType}}">
                            {{ __('locale.Edit') }}
                        </button>
                    </div>
                </div>
            </td>
        </tr>

    @empty
        <tr>
            @if($searchTerm == '')
                <td colspan="20" class="text-center"><p
                        class="alert alert-dismissible">{{ __('locale.There is no connections') }}</p></td>
            @else
                <td colspan="20" class="text-center"><p
                        class="alert alert-dismissible">{{ __('locale.There is no connections that matches') }}
                        "{{ $searchTerm }}"</p></td>
            @endif
        </tr>
    @endforelse

    </tbody>
</table>

{{-- spinner for load more --}}
<div>
    @if(!empty($connections))
        @if(count($connections) && !$showLoader)
            {{ $connections->links() }}
        @elseif($showLoader)
            <div class="load-more-spinner">
                <div id="div-load-more-spinner"
                     class="d-flex justify-content-center align-items-center {{--invisible--}}">
                    <span
                        style="color: #475F7B; font-size: 10px;font-weight: bold;">{{ strtoupper( __('locale.Loading More')) }}</span>
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
            </div>
        @endif
    @endif
</div>
