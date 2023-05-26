<div>
    {{-- not charged print view table--}}
    <div class="print-view-notCharged-table-">
        @if(count($notCharged))
            <table class="print-view-table">
                <thead>
                <tr>
                    <th style="border-bottom: white;"></th>
                    @foreach($headers as $key => $value)
                        <th style="border-bottom: white;"
                            @if($key == 'start' || $key == 'end' || $key == 'devicename' || $key == 'username') width="12%"
                            @elseif($key == 'duration' || $key == 'units' || $key == 'price' || $key == 'billing_state' || $key == 'tariff') width="7%"
                            @endif
                            @if($key == 'devicename' || $key == 'username') class="text-justify" @endif>
                            <span style="letter-spacing: -1px;">{{ __('locale.'.$value) }}</span>
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="table-charged-identifier" colspan="10"
                        style="text-align: justify;border-top: white;border-left: white; border-right: white;padding-bottom: 0;">
                        <strong>{{ __('locale.Uncharged Activities:') }}</strong>
                    </td>
                </tr>
                @foreach($notCharged as $item)
                    <tr>
                        <td class="p-0">
                            @if($item->isTV)
                                <img style="width: 20px;height: 20px;"
                                     src="{{asset('assets/images/ico/icon_anydesk_64.png')}}" alt="tv-icon">
                            @else
                                <i class="bx bx-plus"></i>
                            @endif
                        </td>
                        <td><strong>{{ strtoupper(__('locale.'.substr($item->charsForStartDate(),0,2))) }}</strong>
                            - @datetime($item->start_date)</td>
                        <td><strong>{{ strtoupper(__('locale.'.substr($item->charsForEndDate(),0,2))) }}</strong>
                            - @datetime($item->end_date)</td>
                        <td>{{ $item->duration() }}m</td>
                        <td>{{ $item->calculateUnit() ?? '-' }}</td>
                        <td>{{ $item->price ? $item->price.' €' : '-' }}</td>
                        <td class="text-justify">{{ $item->devicename }}</td>
                        <td class="text-justify">{{ $item->username }}</td>
                        <td style="padding-bottom: 12px;">
                            <fieldset>
                                <div class="checkbox" style="margin-left: 10px;" id="change-bill-{{ $item->id }}">
                                    <input type="checkbox" id="checkbox-bill-{{ $item->id }}"
                                           class="checkbox-input change-bill-checkbox" style="border-color: #495057;"
                                           {{ $item->billing_state == 'Bill' ? 'checked' : '' }}
                                           data-connection="{{$item->id}}">
                                    <label id="label-charged-checkboxes"
                                           for="checkbox-bill-{{ $item->id }}"></label>
                                </div>
                                <div class="d-none" id="change-bill-spinner-{{ $item->id }}" style="display: inline-flex;">
                                    <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
                                </div>
                            </fieldset>
                        </td>
                        <td>
                            @if($item->tariff()->exists())
                                <span class="bullet bullet-sm bullet-primary"
                                      style="width: 5px;height: 5px;background-color: {{ $item->tariff->color }};"></span>
                                {{ $item->tariff()->first()['tariff_name'] }}
                            @endif
                        </td>
                        <td class="text-left">{{ $item->notes }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- charged print view table--}}
    <div class=print-view-charged-table>
        @if(count($charged))
            <table class="print-view-table">
                <thead>
                <tr @if(count($notCharged)) class="invisible" @endif>
                    <th style="border-bottom: white;"></th>
                    @foreach($headers as $key => $value)
                        <th style="border-bottom: white;"
                            @if($key == 'start' || $key == 'end' || $key == 'devicename' || $key == 'username') width="12%"
                            @elseif($key == 'duration' || $key == 'units' || $key == 'price' || $key == 'billing_state' || $key == 'tariff') width="7%"
                            @endif
                            @if($key == 'devicename' || $key == 'username') class="text-justify" @endif>
                            <span style="letter-spacing: -1px;">{{ __('locale.'.$value) }}</span>
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="table-charged-identifier" colspan="10"
                        style="text-align: justify;border-top: white;border-left: white; border-right: white;padding-bottom: 0;
                        @if(count($notCharged)) padding-top: 0; @endif">
                        <strong>{{ __('locale.Charged Activities:') }}</strong>
                    </td>
                </tr>
                @foreach ($charged as $item)
                    <tr>
                        <td class="p-0">
                            @if($item->isTV)
                                <img style="width: 20px;height: 20px;"
                                     src="{{asset('images/ico/icon_anydesk_64.png')}}" alt="tv-icon">
                            @else
                                <i class="bx bx-plus"></i>
                            @endif
                        </td>
                        <td><strong>{{ strtoupper(__('locale.'.substr($item->charsForStartDate(),0,2))) }}</strong>
                            - @datetime($item->start_date)</td>
                        <td><strong>{{ strtoupper(__('locale.'.substr($item->charsForEndDate(),0,2))) }}</strong>
                            - @datetime($item->end_date)</td>
                        <td>{{ $item->duration() }}m</td>
                        <td>{{ $item->calculateUnit() ?? '-' }}</td>
                        <td>{{ $item->price ? $item->price.' €' : '-' }}</td>
                        <td class="text-justify">{{ $item->devicename }}</td>
                        <td class="text-justify">{{ $item->username }}</td>
                        <td style="padding-bottom: 12px;">
                            <fieldset>
                                <div class="checkbox" style="margin-left: 10px;" id="change-bill-{{ $item->id }}">
                                    <input type="checkbox" id="checkbox-bill-{{ $item->id }}"
                                           class="checkbox-input change-bill-checkbox" style="border-color: #495057;"
                                           {{ $item->billing_state == 'Bill' ? 'checked' : '' }}
                                           data-connection="{{$item->id}}">
                                    <label id="label-charged-checkboxes"
                                           for="checkbox-bill-{{ $item->id }}"></label>
                                </div>
                                <div class="d-none" id="change-bill-spinner-{{ $item->id }}" style="display: inline-flex;">
                                    <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
                                </div>
                            </fieldset>
                        </td>
                        <td>
                            @if($item->tariff()->exists())
                                <span class="bullet bullet-sm bullet-primary"
                                      style="width: 5px;height: 5px;background-color: {{ $item->tariff->color }};"></span>
                                {{ $item->tariff()->first()['tariff_name'] }}
                            @endif
                        </td>
                        <td class="text-left">{{ $item->notes }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
