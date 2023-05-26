@php
    $isAdmin = auth()->user()->hasRole('Admin');
@endphp
@foreach($connections as $item)
    @php
        $activityType = $item->isTV == true ? 'edit-connection' : 'manual-activity';
        $itemHasBorderLineEmergency = $item->overlaps_tariff == true && $item->is_tariff_overlap_confirmed == false;
        /*if($selectedStatus == 5){
            $itemHasBorderLineEmergency = false;
        }*/
    @endphp
    <tr style="@if($itemHasBorderLineEmergency) background-color: hsl(64, 76%, 85%); @elseif($item->overlaps_user) background-color: hsl(356, 76%, 95%); @endif"> {{--hsla(138, 62%, 89%, 1)--}}
        <td class="text-center pl-0 pr-0">
            <div x-data class="div-row-checkbox-fieldset-{{ $item->id }}">
                <fieldset class="row-checkbox-fieldset">
                    <div class="checkbox">
                        <input {{ $isAdmin ? '' : 'disabled' }} type="checkbox"
                               id="row-checkbox-connection-{{ $item->id }}" class="checkbox-input"
                               style="border-color: #495057;"
                               {{--@if(array_key_exists($item->id,$selectedRowCheckbox) || array_key_exists($item->id,$unSelectedRowCheckbox)) checked
                               @endif--}}
                               wire:click="checkboxSelected('{{ $item->id }}')" value="{{ $item->id }}">
                        <label for="row-checkbox-connection-{{ $item->id }}"></label>
                    </div>
                </fieldset>
            </div>
            <div class="row-checkbox-connection-invisible-{{ $item->id }} d-none">
                <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm"
                                               style="color: #495057;"></x-loading.ball-spin-clockwise>
            </div>
        </td>
        {{--                                            <td class="pl-0 pr-0">--}}
        {{--                                                <i wire:click="redirectBorderLineComponent('{{ $item->id }}')" class="bx bx-show-alt" style="font-size: 10px; color: red;@if(!$this->borderlineCheck($item->id)) display: none; @else cursor: pointer; @endif" title="{{ __('locale.Border Line Emergence') }}"></i>--}}
        {{--                                            </td>--}}
        <td class="p-0 edit-connection-item" data-connection="{{$item->id}}"
            data-connection-type="{{$activityType}}">
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
                <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm"
                                               style="color: #495057;"></x-loading.ball-spin-clockwise>
            </div>
        </td>
        <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}"
            class="text-center edit-connection-item" style="padding: 3px;">
            <strong>{{ strtoupper(__('locale.'.substr($item->charsForStartDate(),0,2))) }}</strong>
            - {{ $item->start_date->setTimezone(config('site.default_timezone'))->format('d.m.Y') }} @if($item->overlaps_color)
                <strong>{{ $item->start_date->setTimezone(config('site.default_timezone'))->format('H:i') }}</strong> @else {{ $item->start_date->setTimezone(config('site.default_timezone'))->format('H:i') }} @endif
        </td>
        <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}"
            class="text-center edit-connection-item">
            <strong>{{ strtoupper(__('locale.'.substr($item->charsForEndDate(),0,2))) }}</strong>
            - {{ $item->end_date->setTimezone(config('site.default_timezone'))->format('d.m.Y') }} @if($item->overlaps_color)
                <strong>{{ $item->end_date->setTimezone(config('site.default_timezone'))->format('H:i') }}</strong> @else {{ $item->end_date->setTimezone(config('site.default_timezone'))->format('H:i') }} @endif
        </td>

        {{-- duration without interval --}}
        <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}"
            class="text-center edit-connection-item">{{ $item->getWithoutIntervalDuration() }}
        </td>

        {{-- duration with interval --}}
        <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}"
            class="text-center edit-connection-item with-tarif-interval">{{ $item->getWithIntervalDuration() }}
        </td>

        @role('Admin')
        <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}"
            class="text-center edit-connection-item">{{ $item->calculateUnit() ?? '-' }}</td>
        <td data-connection="{{$item->id}}" data-connection-type="{{$activityType}}"
            class="text-center text-nowrap edit-connection-item">{{ $item->price ? $item->price.' €' : '-' }}</td>
        @endrole

        @if(!$customer)
            <td class="edit-connection-item" data-connection="{{$item->id}}"
                data-connection-type="{{$activityType}}">{{ $item->groupname }}</td>
        @endif

        <td class="text-wrap edit-connection-item" data-connection="{{$item->id}}"
            data-connection-type="{{$activityType}}">{{ $item->devicename }}</td>
        <td class="edit-connection-item" data-connection="{{$item->id}}"
            data-connection-type="{{$activityType}}">{{ $item->username }}</td>
        <td class="text-center bill-column" wire:key="-loading-billing_state-{{ $item->id }}-{{ time() }}">
            <fieldset class="bill-checkbox-fieldset">
                <div class="checkbox" id="change-bill-{{ $item->id }}">
                    <input {{ $item->trashed() ? 'disabled' : '' }} type="checkbox" data-connection="{{ $item->id }}" id="checkbox-bill-{{ $item->id }}"
                           class="checkbox-input change-bill-checkbox" style="border-color: #495057;" {{ $item->billing_state == 'Bill' ? 'checked' : '' }}>
                    <label for="checkbox-bill-{{ $item->id }}"></label>
                </div>
                <div class="d-none" id="change-bill-spinner-{{ $item->id }}" style="display: inline-flex;">
                    <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
                </div>
            </fieldset>
        </td>

        @if($isAdmin)
            <td width="10%" class="text-center">
                @if($item->tariff)
                    <span class="bullet bullet-sm bullet-primary"
                          style="width: 5px;height: 5px;background-color: {{ $item->tariff->color }};"></span>
                    {{ !empty($item->tariff->tariff_name) ? $item->tariff->tariff_name : '' }}
                @elseif(!count($item->matchingTariff($item->start_date)))
                    <div class="badge badge-light-danger tariff-conflicts-badge">Tariff Conflicts</div>
                @endif
            </td>
        @endif

        <td width="12%" style="width: 10%;">{{ $item->narrowStringLenghtForNotesColumn() }}</td>
        <td class="actions-column custom-action-all-td" colspan="3" style="padding: 0.2rem 0;"
            wire:key="-loading-action_buttons-{{ $item->id }}-{{ time() }}">
            @if($loop->first)
                <div class="d-flex justify-content-center align-items-center custom-action-all">
                    <div class="btn-group dropleft dropdown">
                        <button type="button" class="btn btn-sm btn-icon button-row-conn-loading-state"
                                id="dropdownMenuOffset"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                data-offset="5,20">
                            <i id="dots-icon-for-buttons-global"
                               class="bx bx-dots-horizontal-rounded"></i> {{--wire:loading.remove wire:target="openOverlapsModal('{{ $item->id }}'),openActivityModal('{{ $item->id }}')"--}}
                            <div class="div-button-loading-state-global d-none"
                                 style="color: black;width: 100%;height: 100%;">
                                <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm"
                                                               style="color: #495057;"></x-loading.ball-spin-clockwise>
                            </div>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset" style="width: 20px;">
                            <button class="dropdown-item">
                                    <span
                                        style="@if(!$item->overlaps_color) color: lightgrey; @endif">{{__('locale.Overlaps')}}</span>
                            </button>
                            <button class="dropdown-item">
                                <span>{{__('locale.Splitting')}}</span>
                            </button>
                            <button class="dropdown-item">
                                {{ __('locale.Edit') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            <div class="d-flex justify-content-center align-items-center">
                <div class="btn-group dropleft dropdown">
                    <button type="button" class="btn btn-sm btn-icon button-row-conn-loading-state"
                            id="dropdownMenuOffset"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="5,20">
                        <i id="dots-icon-for-buttons-{{ $item->id }}"
                           class="bx bx-dots-horizontal-rounded"></i> {{--wire:loading.remove wire:target="openOverlapsModal('{{ $item->id }}'),openActivityModal('{{ $item->id }}')"--}}
                        <div class="div-button-loading-state-{{ $item->id }} d-none"
                             style="color: black;width: 100%;height: 100%;">
                            <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm"
                                                           style="color: #495057;"></x-loading.ball-spin-clockwise>
                        </div>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset" style="width: 20px;">
                        <button
                            {{ !$item->overlaps_color ? 'disabled' : '' }} class="dropdown-item {{$item->overlaps_color ? 'time-overlapped-connection' : ''}}"
                            data-connection="{{ $item->id }}">
                                <span
                                    style="@if(!$item->overlaps_color) color: lightgrey; @endif">{{__('locale.Overlaps')}}</span>
                        </button>
                        <button
                            {{ !$itemHasBorderLineEmergency ? 'disabled' : '' }} class="dropdown-item {{ $itemHasBorderLineEmergency ? 'split-connection' : '' }}"
                            data-connection="{{ $item->id }}">
                                <span
                                    style="@if(!$itemHasBorderLineEmergency) color: lightgrey; @endif">{{__('locale.Splitting')}}</span>
                        </button>
                        <button
                            {{ !$itemHasBorderLineEmergency ? 'disabled' : '' }} class="dropdown-item  {{ $itemHasBorderLineEmergency ? 'confirm-connection-tariff' : '' }}"
                            data-connection="{{ $item->id }}">
                                    <span
                                        style="@if(!$itemHasBorderLineEmergency) color: lightgrey; @endif">{{__('locale.Select tariff')}}</span>
                        </button>
                        <button class="dropdown-item edit-connection-item" data-connection="{{$item->id}}"
                                data-connection-type="{{$activityType}}">
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
@endforeach
