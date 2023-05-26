<div class="statistic-right">
    <div class="card shadow-none">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex justify-content-between align-items-center">
                {{ __('locale.Top 5 Customer with extra demand') }}

                @if ($showWholeList)
                    <a href="{{ route('customers.list') . '?sort=' . $sort }}">
                        {{ __('locale.Watch whole list') }}
                    </a>
                @endif
            </h5>
        </div>

        <div class="card-body px-0 py-1">
            <ul class="nav nav-tabs custom-dashboard-tabs"
                role="tablist">
                <li class="nav-item text-center">
                    <button class="nav-link {{ $tab_selected == 'currentmonth' ? 'active' : '' }}" id="currentmonth-tab"
                       aria-controls="currentmonth"
                       wire:click="setSelectedTab('currentmonth')" aria-selected="true">
                        <span class="align-middle">{{ __('locale.Current Month') }}</span>
                    </button>
                </li>
                <li class="nav-item text-center">
                    <button class="nav-link {{ $tab_selected == 'currentyear' ? 'active' : '' }}" id="currentyear-tab"
                       aria-controls="currentyear"
                       wire:click="setSelectedTab('currentyear')" aria-selected="true">
                        <span class="align-middle">{{ __('locale.Current Year') }}</span>
                    </button>
                </li>
                <li class="nav-item text-center">
                    <button class="nav-link {{ $tab_selected == 'lastmonth' ? 'active' : '' }}" id="lastmonth-tab"
                       aria-controls="lastmonth"
                       wire:click="setSelectedTab('lastmonth')" aria-selected="false">
                        <span class="align-middle">{{ __('locale.Last Month') }}</span>
                    </button>
                </li>
                <li class="nav-item text-center">
                    <button class="nav-link {{ $tab_selected == 'lastquarter' ? 'active' : '' }}" id="lastquarter-tab"
                       aria-controls="lastquarter"
                       wire:click="setSelectedTab('lastquarter')" aria-selected="false">
                        <span class="align-middle">{{ __('locale.Last Quarter') }}</span>
                    </button>
                </li>
                <li class="nav-item text-center">
                    <button class="nav-link {{ $tab_selected == 'lastyear' ? 'active' : '' }}" id="lastyear-tab"
                       aria-controls="lastyear"
                       wire:click="setSelectedTab('lastyear')" aria-selected="false">
                        <span class="align-middle">{{ __('locale.Last Year') }}</span>
                    </button>
                </li>
            </ul>
            <div class="tab-content pl-0">
                {{-- current month --}}
                <div class="tab-pane {{ $tab_selected == 'currentmonth' ? 'active' : '' }}"
                     id="currentmonth" aria-labelledby="currentmonth-tab" role="tabpanel">
                    @if (!empty($topCustomers['current_month']))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="fs-10 text-left" style="width: 50%;">
                                            {{__('locale.Customer Name')}}
                                        </th>
                                        <th class="fs-10" style="width: 25%;">
                                            {{__('locale.Planned Operating Time')}}
                                        </th>
                                        <th class="fs-10" style="width: 25%;">
                                            {{__('locale.Actual Operating Time')}}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Helper::sortArrayByValue($topCustomers['current_month'], 'ot_diff') as $customerId => $currentMonthCustomer)
                                        <tr>
                                            <td class="text-left">
                                                {{ $loop->iteration }} . <a href="{{ route('customers.show', $customerId) }}">{{ $currentMonthCustomer['customer_name'] }}</a>
                                            </td>
                                            <td>
                                                {{ !empty($currentMonthCustomer['planned_operating_time']) ? \App\Helpers\Helper::formatHoursAndMinutes($currentMonthCustomer['planned_operating_time'], '%02d:%02dh') : '00:00h' }}
                                            </td>
                                            <td>
                                                {{ !empty($currentMonthCustomer['curr_month_actual_operate_time']) ? \App\Helpers\Helper::formatHoursAndMinutes($currentMonthCustomer['curr_month_actual_operate_time'], '%02d:%02dh') : '00:00h' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">{{ __('locale.No results found') }}</p>
                    @endif
                </div>
                {{-- Current Year --}}
                <div class="tab-pane {{ $tab_selected == 'currentyear' ? 'active' : '' }}"
                     id="currentyear" aria-labelledby="currentyear-tab" role="tabpanel">
                    @if (!empty($topCustomers['current_year']))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="fs-10 text-left" style="width: 50%;">
                                            {{__('locale.Customer Name')}}
                                        </th>
                                        <th class="fs-10" style="width: 25%;">
                                            {{__('locale.Planned Operating Time')}}
                                        </th>
                                        <th class="fs-10" style="width: 25%;">
                                            {{__('locale.Actual Operating Time')}}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Helper::sortArrayByValue($topCustomers['current_year'], 'ot_diff') as $customerId => $currentYearCustomer)
                                        <tr>
                                            <td class="text-left">
                                                {{ $loop->iteration }} . <a href="{{ route('customers.show', $customerId) }}">{{ $currentYearCustomer['customer_name'] }}</a>
                                            </td>
                                            <td>
                                                {{ !empty($currentYearCustomer['planned_operating_time']) ? \App\Helpers\Helper::formatHoursAndMinutes($currentYearCustomer['planned_operating_time'], '%02d:%02dh') : '00:00h' }}
                                            </td>
                                            <td>
                                                {{ !empty($currentYearCustomer['current_year_actual_operate_time']) ? \App\Helpers\Helper::formatHoursAndMinutes($currentYearCustomer['current_year_actual_operate_time'], '%02d:%02dh') : '00:00h' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">{{ __('locale.No results found') }}</p>
                    @endif
                </div>
                {{-- last month --}}
                <div class="tab-pane {{ $tab_selected == 'lastmonth' ? 'active' : '' }}" id="lastmonth"
                     aria-labelledby="lastmonth-tab" role="tabpanel">
                    @if (!empty($topCustomers['last_month']))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="fs-10 text-left" style="width: 50%;">
                                            {{__('locale.Customer Name')}}
                                        </th>
                                        <th class="fs-10" style="width: 25%;">
                                            {{__('locale.Planned Operating Time')}}
                                        </th>
                                        <th class="fs-10" style="width: 25%;">
                                            {{__('locale.Actual Operating Time')}}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Helper::sortArrayByValue($topCustomers['last_month'], 'ot_diff') as $customerId => $lastMonthCustomer)
                                        <tr>
                                            <td class="text-left">
                                                {{ $loop->iteration }} . <a href="{{ route('customers.show', $customerId) }}">{{ $lastMonthCustomer['customer_name'] }}</a>
                                            </td>
                                            <td>
                                                {{ !empty($lastMonthCustomer['planned_operating_time']) ? \App\Helpers\Helper::formatHoursAndMinutes($lastMonthCustomer['planned_operating_time'], '%02d:%02dh') : '00:00h' }}
                                            </td>
                                            <td>
                                                {{ !empty($lastMonthCustomer['last_month_actual_operate_time']) ? \App\Helpers\Helper::formatHoursAndMinutes($lastMonthCustomer['last_month_actual_operate_time'], '%02d:%02dh') : '00:00h' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">{{ __('locale.No results found') }}</p>
                    @endif
                </div>
    
                {{-- last quarter --}}
                <div class="tab-pane {{ $tab_selected == 'lastquarter' ? 'active' : '' }}" id="lastquarter"
                     aria-labelledby="lastquarter-tab" role="tabpanel">
                    @if (!empty($topCustomers['last_quarter']))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="fs-10 text-left" style="width: 50%;">
                                            {{__('locale.Customer Name')}}
                                        </th>
                                        <th class="fs-10" style="width: 25%;">
                                            {{__('locale.Planned Operating Time')}}
                                        </th>
                                        <th class="fs-10" style="width: 25%;">
                                            {{__('locale.Actual Operating Time')}}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Helper::sortArrayByValue($topCustomers['last_quarter'], 'ot_diff') as $customerId => $lastQuarterCustomer)
                                        <tr>
                                            <td class="text-left">
                                                {{ $loop->iteration }} . <a href="{{ route('customers.show', $customerId) }}">{{ $lastQuarterCustomer['customer_name'] }}</a>
                                            </td>
                                            <td>
                                                {{ !empty($lastQuarterCustomer['planned_operating_time']) ? \App\Helpers\Helper::formatHoursAndMinutes($lastQuarterCustomer['planned_operating_time'], '%02d:%02dh') : '00:00h' }}
                                            </td>
                                            <td>
                                                {{ !empty($lastQuarterCustomer['last_quarter_actual_operate_time']) ? \App\Helpers\Helper::formatHoursAndMinutes($lastQuarterCustomer['last_quarter_actual_operate_time'], '%02d:%02dh') : '00:00h' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">{{ __('locale.No results found') }}</p>
                    @endif
                </div>
    
                {{-- last year --}}
                <div class="tab-pane {{ $tab_selected == 'lastyear' ? 'active' : '' }}" id="lastyear" aria-labelledby="lastyear-tab" role="tabpanel">
                    @if (!empty($topCustomers['last_year']))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="fs-10 text-left" style="width: 50%;">
                                            {{__('locale.Customer Name')}}
                                        </th>
                                        <th class="fs-10" style="width: 25%;">
                                            {{__('locale.Planned Operating Time')}}
                                        </th>
                                        <th class="fs-10" style="width: 25%;">
                                            {{__('locale.Actual Operating Time')}}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Helper::sortArrayByValue($topCustomers['last_year'], 'ot_diff') as $customerId => $lastYearCustomer)
                                        <tr>
                                            <td class="text-left">
                                                {{ $loop->iteration }} . <a href="{{ route('customers.show', $customerId) }}">{{ $lastYearCustomer['customer_name'] }}</a>
                                            </td>
                                            <td>
                                                {{ !empty($lastYearCustomer['planned_operating_time']) ? \App\Helpers\Helper::formatHoursAndMinutes($lastYearCustomer['planned_operating_time'], '%02d:%02dh') : '00:00h' }}
                                            </td>
                                            <td>
                                                {{ !empty($lastYearCustomer['last_year_actual_operate_time']) ? \App\Helpers\Helper::formatHoursAndMinutes($lastYearCustomer['last_year_actual_operate_time'], '%02d:%02dh') : '00:00h' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">{{ __('locale.No results found') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
