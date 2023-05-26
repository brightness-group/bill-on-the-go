@section('title', __('locale.Customers'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ mix('assets/css/customer_component_css.css') }}" />
@endsection

@push('styles')
    <livewire:modals />
@endpush

<section>
    <div class="row">
        <div class="card card-data">
            <div class="card-header d-flex justify-content-between align-items-center pb-0">
                <h3><strong>{{ __('locale.Customers Overview') }}</strong></h3>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        @if (!$connected)
                        <div class="col-12 d-flex flex-row-reverse mb-2">
                            <a href="{{ route('account.settings').'#account-vertical-connect' }}" class="nav-link" style="padding: unset;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                    <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3" />
                                </svg>
                            </a>
                            <div class="me-1">{{ __('locale.Connection to Teamviewer') }}</div>
                        </div>
                        @endif
                        <div class="col-12 d-flex flex-row-reverse">
                            @if (auth()->user()->hasRole('Admin'))
                                <div class="text-nowrap">
                                    <a class="btn btn-outline-dark" href="{{ route('customers.show') }}">
                                        <div class="justify-content-between align-items-center">
                                            <i class="bx bx-plus me-sm-2"></i>
                                            <span>{{ __('locale.New') }}</span>
                                        </div>
                                    </a>
                                </div>
                                {{--
                                    <div style="margin-right: 5px;" wire:key="loading-container"
                                        class="text-nowrap @if (!$connected && !$alertExpireToken) invisible @endif">
                                        <button {{ !$connected && $alertExpireToken ? 'disabled' : '' }}
                                            class="btn btn-outline-danger" title="{{ __('locale.Refresh') }}"
                                            wire:click="retrieveFromAPI" wire:loading.attr="disabled"><i
                                                class="bx bx-cloud-download" style="margin-right: 2px;"></i>
                                            <span wire:loading.remove
                                                wire:target="retrieveFromAPI">{{ __('locale.Download') }}</span>
                                            <span wire:loading
                                                wire:target="retrieveFromAPI">{{ __('locale.Downloading') }}...</span>
                                        </button>
                                    </div>
                                 --}}
                                <div style="margin-right: 5px;"
                                    class="text-nowrap  {{ $selectedAll || count($selectedRowArchieveActive) > 1 ? '' : 'invisible' }}">
                                    <button wire:click="openCustomersSummarizeModal" class="btn btn-outline-secondary"
                                        wire:loading.attr="disabled" title="{{ __('locale.Summarize') }}">
                                        <i class="bx bx-transfer"
                                            style="margin-right: 2px;"></i><span>{{ __('locale.Summarize') }}</span>
                                    </button>
                                </div>
                            @endif
                            {{-- <div class="custom-control align-items-center"
                                title="@if (!$notification) {{ __('locale.Show Notifications') }} @else {{ __('locale.Hide Notifications') }} @endif">
                                <label class="switch switch-dark switch-lg">
                                    <input type="checkbox" class="custom-control-input switch-input" checked="" id="customSwitch10" wire:model="notification" />
                                    <span class="custom-control-label form-check-label switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-bell"></i>
                                        </span>
                                    </span>
                                </label>
                            </div> --}}
                            <div style="margin-right: 5px;">
                                <select class="form-select" wire:model="selectedStatus">
                                    <option value="1">{{ __('locale.Active') }}</option>
                                    <option value="2">{{ __('locale.Inactive') }}</option>
                                </select>
                            </div>
                            <div style="margin-right: 5px;"
                                 data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{$selectedAll || count($selectedRowArchieveActive) ? '' : __('locale.Please select customer')}}"
                                 data-bs-custom-class="tooltip-secondary">
                                <button
                                    class="btn {{ $selectedAll || count($selectedRowArchieveActive) ? 'btn-warning' : 'btn-secondary' }}"
                                    wire:click.prevent="selectedRowActions"
                                    onclick="confirm('{{ __('locale.Are you sure?') }}') || event.stopImmediatePropagation()"
                                    {{ $selectedAll || count($selectedRowArchieveActive) ? '' : 'disabled' }}>
                                    @if ($selectedStatus == 1)
                                        {{ __('locale.Deactivate') }}
                                    @elseif($selectedStatus == 2)
                                        {{ __('locale.Activate') }}
                                    @else
                                        {{ __('locale.Save') }}
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Archieved Customer Modal -->
                    <div class="modal fade" id="customerArchievedModal" tabindex="-1"
                        aria-labelledby="customerArchievedModalLabel" aria-hidden="true" data-keyboard="false"
                        data-backdrop="static">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
                                    <h5 class="modal-title" id="customerArchievedModalLabel">
                                        {{ __('locale.File Customer') }}</h5>
                                    <button type="button" class="close" style="background-color: #c9c9c9"
                                        wire:click="closeCustomerArchievedModal" aria-label="{{ __('locale.Close') }}">
                                        <span aria-hidden="true"><strong>&times;</strong></span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h3>{{ __('locale.Are you sure?') }}</h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        wire:click="closeCustomerArchievedModal">{{ __('locale.Cancel') }}</button>
                                    <button type="button" class="btn btn-dark" wire:click="archieved"
                                        wire:loading.attr="disabled">{{ __('locale.Yes') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activate Customer Modal -->
                    <div class="modal fade" id="customerActivateModal" tabindex="-1"
                        aria-labelledby="customerActivateModalLabel" aria-hidden="true" data-keyboard="false"
                        data-backdrop="static">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
                                    <h5 class="modal-title" id="customerActivateModalLabel">
                                        {{ __('locale.Activate Customer') }}</h5>
                                    <button type="button" class="close" style="background-color: #c9c9c9"
                                        wire:click="closeCustomerActivateModal" aria-label="{{ __('locale.Close') }}">
                                        <span aria-hidden="true"><strong>&times;</strong></span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h3>{{ __('locale.Are you sure?') }}</h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        wire:click="closeCustomerActivateModal">{{ __('locale.Cancel') }}</button>
                                    <button type="button" class="btn btn-dark" wire:click="activate"
                                        wire:loading.attr="disabled">{{ __('locale.Yes') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Customer Modal -->
                    <div class="modal fade" id="customerDeleteModal" tabindex="-1"
                        aria-labelledby="customerDeleteModalLabel" aria-hidden="true" data-keyboard="false"
                        data-backdrop="static">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
                                    <h5 class="modal-title" id="customerDeleteModalLabel">
                                        {{ __('locale.Delete Customer') }}</h5>
                                    <button type="button" class="close" style="background-color: #c9c9c9"
                                        wire:click="closeCustomerDeleteModal" aria-label="{{ __('locale.Close') }}">
                                        <span aria-hidden="true"><strong>&times;</strong></span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h3>{{ __('locale.Are you sure?') }}</h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        wire:click="closeCustomerDeleteModal">{{ __('locale.Cancel') }}</button>
                                    <button type="button" class="btn btn-dark" wire:click="destroy"
                                        wire:loading.attr="disabled">{{ __('locale.Yes') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br />

                    <div class="custom-table-for-customers">
                        <div class="spinner-border-wrapper" wire:loading wire:target="calculateActualOperatingTime">
                            <div class="spinner-border"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover customers-table table-bordered custom-sorting">
                                <thead>
                                    <tr>
                                        <th colspan="6" class="border-bottom-0">
                                            @livewire('search-field-component', ['search' => $search])
                                        </th>
                                        <th colspan="5" class="text-center border-bottom-0">
                                            {{ __('locale.Actual Operating Time') }}
                                            {{-- <button {{ !$enableOperatingTimeButton ? '' : 'disabled' }}
                                                class="btn btn-outline-danger calculate-operating-time-btn"
                                                title="{{ __('locale.Calculate Actual Operating Time') }}"
                                                wire:click="calculateActualOperatingTime" wire:loading.attr="disabled">
                                                <i wire:loading.remove wire:target="calculateActualOperatingTime"
                                                    class="bx bxs-cloud-download" style="margin-right: 2px;"></i>
                                                <span wire:loading wire:target="calculateActualOperatingTime"
                                                    title="{{ __('locale.Processing') }}">
                                                    <i class="bx bx-cloud-download" style="margin-right: 2px;"></i>
                                                </span>
                                            </button> --}}
                                            <span class="pl-4"
                                                title="{{ __('locale.All times are given in the format hour : minute') }}"><i
                                                    class="ficon bx bxs-help-circle"></i></span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td style="white-space: nowrap;width: 1%;" title="{{ __('locale.All') }}">
                                            <fieldset>
                                                <div class="checkbox"> {{--  --}}
                                                    <input
                                                        {{ auth()->user()->hasRole('Admin') &&$selectedStatus &&count($this->selectedInputCollection()->items())? '': 'disabled' }}
                                                        type="checkbox" wire:model="selectedAll"
                                                        id="checkbox-all-trash-customer-component" class="checkbox-input form-check-input">
                                                    <label for="checkbox-all-trash-customer-component"></label>
                                                </div>
                                            </fieldset>
                                        </td>
                                        @foreach ($headers as $key => $title)
                                            <th wire:click="sort('{{ $key }}')" style="{{ in_array($key, $typeCastFields) ? 'padding: 10px 10px;' : '' }}" class="{{ in_array($key, $typeCastFields) ? 'text-center' : '' }}">
                                                @if ($sortColumn == $key)
                                                    {{-- <span wire:key="sort-key-{{ $key }}">{!! $sortDirection == 'asc' ? '&#8679' : '&#8681' !!}</span> --}}
                                                    <span class="top-row">
                                                        <i class='bx bx-chevron-up {{ $sortDirection == 'asc' ? 'active' : '' }}'></i>
                                                    </span>
                                                    <span class="bottom-row">
                                                        <i class='bx bx-chevron-down {{ $sortDirection == 'asc' ? '' : 'active' }}'></i>
                                                    </span>
                                                @endif
                                                <span style="letter-spacing: -1px;">
                                                    {{ __('locale.' . $title) }}
                                                </span>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($customers))
                                        @foreach ($customers as $customer)
                                            <tr>
                                                <td class="text-center" title="@if ($customer->trashed()) {{ __('locale.Activate') }} @else {{ __('locale.Archive') }} @endif">
                                                    <fieldset>
                                                        <div class="checkbox"> {{-- && count($collection->items()) --}}
                                                            <input
                                                                {{ auth()->user()->hasRole('Admin') && $selectedStatus? '': 'disabled' }}
                                                                type="checkbox"
                                                                wire:model="selectedRowArchieveActive.{{ $customer->id }}"
                                                                id="checkbox-trash-{{ $customer->id }}" class="checkbox-input form-check-input"
                                                                value="{{ $customer->id }}">
                                                            <label for="checkbox-trash-{{ $customer->id }}"></label>
                                                        </div>
                                                    </fieldset>
                                                </td>
                                                <td>
                                                    @if ($notification && $this->markAsIncompleteData($customer->id)) <span style="color: red;font-weight: bold">!</span> @endif
                                                    <div class="d-inline-flex">
                                                        <label @if (!$customer->trashed()) onclick="window.location='{{ route('customers.show', ['customer' => $customer]) }}'" style="cursor: pointer;" @endif @if ($notification && $this->markAsIncompleteData($customer->id)) title="{{ __('locale.Incomplete information') }}: {{ $this->incompleteDataFilled($customer) }}" @endif>
                                                            {{ $customer->customer_name }}
                                                        </label>

                                                        &nbsp;

                                                        <a class="nav-link" style="padding: unset;" href="{{ route('customer.connections', $customer->bdgogid). '?ff=1' }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link">
                                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                                <polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td @if (!$customer->trashed()) onclick="window.location='{{ route('customers.show', ['customer' => $customer]) }}'" style="cursor: pointer" @endif>{{ $customer->email }}</td>
                                                <td @if (!$customer->trashed()) onclick="window.location='{{ route('customers.show', ['customer' => $customer]) }}'" style="cursor: pointer" @endif>{{ $customer->phone }}</td>
                                                <td @if (!$customer->trashed()) onclick="window.location='{{ route('customers.show', ['customer' => $customer]) }}'" style="cursor: pointer" @endif>{{ $customer->city }}</td>
                                                <td style="cursor: pointer">
                                                    @livewire('tenant.editable-component',['id'=>$customer->id,'model_name' => 'Customer', 'model_id' =>
                                                    $customer->id, 'selected_field_name' => '', 'selected_field_value' =>
                                                    ($customer->planned_operating_time ? $customer->planned_operating_time : '')],
                                                    key($customer->id .'-'. time()))
                                                </td>
                                                @if( str_contains($customer->curr_month_actual_operate_time,',')
                                                || str_contains($customer->last_month_actual_operate_time,',')
                                                || str_contains($customer->last_quarter_actual_operate_time,',')
                                                || str_contains($customer->current_year_actual_operate_time,',')
                                                || str_contains($customer->last_year_actual_operate_time,',')
                                                )
                                                    <td style="padding: 10px 10px;" class="text-center">{{ !empty($customer->curr_month_actual_operate_time) ? $customer->curr_month_actual_operate_time: '00:00 h' }}
                                                    </td>
                                                    <td style="padding: 10px 10px;" class="text-center">{{ !empty($customer->last_month_actual_operate_time) ? $customer->last_month_actual_operate_time: '00:00 h' }}
                                                    </td>
                                                    <td style="padding: 10px 10px;" class="text-center">{{ !empty($customer->last_quarter_actual_operate_time) ? $customer->last_quarter_actual_operate_time: '00:00 h' }}
                                                    </td>
                                                    <td style="padding: 10px 10px;" class="text-center">{{ !empty($customer->current_year_actual_operate_time) ? $customer->current_year_actual_operate_time: '00:00 h' }}
                                                    </td>
                                                    <td style="padding: 10px 10px;" class="text-center">{{ !empty($customer->last_year_actual_operate_time ) ? $customer->last_year_actual_operate_time : '00:00 h' }}
                                                    </td>
                                                @else
                                                    <td style="padding: 10px 10px;" class="text-center">{{ !empty($customer->curr_month_actual_operate_time) ? \App\Helpers\Helper::formatHoursAndMinutes($customer->curr_month_actual_operate_time,'%02d:%02d h',true) : '00:00 h' }}
                                                    </td>
                                                    <td style="padding: 10px 10px;" class="text-center">{{ !empty($customer->last_month_actual_operate_time) ? \App\Helpers\Helper::formatHoursAndMinutes($customer->last_month_actual_operate_time,'%02d:%02d h',true) : '00:00 h' }}
                                                    </td>
                                                    <td style="padding: 10px 10px;" class="text-center">{{ !empty($customer->last_quarter_actual_operate_time) ? \App\Helpers\Helper::formatHoursAndMinutes($customer->last_quarter_actual_operate_time,'%02d:%02d h',true) : '00:00 h' }}
                                                    </td>
                                                    <td style="padding: 10px 10px;" class="text-center">{{ !empty($customer->current_year_actual_operate_time) ? \App\Helpers\Helper::formatHoursAndMinutes($customer->current_year_actual_operate_time,'%02d:%02d h',true) : '00:00 h' }}
                                                    </td>
                                                    <td style="padding: 10px 10px;" class="text-center">{{ !empty($customer->last_year_actual_operate_time) ? \App\Helpers\Helper::formatHoursAndMinutes($customer->last_year_actual_operate_time,'%02d:%02d h',true) : '00:00 h' }}
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            @if ($search == '')
                                                <td colspan="6" class="text-center">
                                                    <p class="alert alert-dismissible" style="font-size: 12px;">
                                                        {{ __('locale.There is no customers') }}</p>
                                                </td>
                                            @else
                                                <td colspan="6" class="text-center">
                                                    <p class="alert alert-dismissible" style="font-size: 12px;">
                                                        {{ __('locale.There is no customers that matches') }}
                                                        "{{ $search }}"</p>
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer" style="float: right;">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

@section('custom_scripts')
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
    <script src="{{ mix('assets/vendor/libs/jquery-mask-plugin/dist/jquery.mask.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            let $locale = "{!! config('app.locale') !!}";
            if ($locale === "de") {
                flatpickr(".timePicker", {
                    enableTime: true,
                    noCalendar: true,
                    time_24hr: true,
                    dateFormat: "H:i",
                    disableMobile: "true",
                    locale: {
                        "locale": "de"
                    }
                });
            }else{
                flatpickr(".timePicker", {
                    enableTime: true,
                    noCalendar: true,
                    time_24hr: true,
                    dateFormat: "H:i",
                    disableMobile: "true",
                });
            }

            Livewire.emit('showToastrMessageRedirected');

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
                toastr.success('', event.detail.message).css("width", "fit-content")
            });

            window.addEventListener('showToastrDelete', event => {
                toastr.warning('', event.detail.message).css("width", "fit-content")
            });

            window.addEventListener('showToastrError', event => {
                toastr.error('', event.detail.message).css("width", "fit-content")
            });

            window.addEventListener('searchUpdate', event => {
                const url = new URL(window.location);

                url.searchParams.set('search', event.detail.search);

                window.history.pushState(null, '', url.toString());
            });

            // window.addEventListener('showToastrTeamviewerError', event => {
            //     toastr.error('',event.detail.message).css("width","fit-content")
            // })

            // setup for sort colun when redirecting from dashboard with params
            /* var enable_sorting = "{{ url()->previous() == route('dashboard') }}";
            var sorting_param = "{{ request()->get('sort') }}";
            if (enable_sorting && sorting_param) {
                Livewire.emitTo('tenant.customer-component', 'sort', sorting_param, 'desc');
            } */
        });

        window.addEventListener('openCustomerArchievedModal', event => {
            $("#customerArchievedModal").modal('show');
        })

        window.addEventListener('closeCustomerArchievedModal', event => {
            $("#customerArchievedModal").modal('hide');
        })

        window.addEventListener('openCustomerActivateModal', event => {
            $("#customerActivateModal").modal('show');
        })

        window.addEventListener('closeCustomerActivateModal', event => {
            $("#customerActivateModal").modal('hide');
        })

        window.addEventListener('openCustomerDeleteModal', event => {
            $("#customerDeleteModal").modal('show');
        })

        window.addEventListener('closeCustomerDeleteModal', event => {
            $("#customerDeleteModal").modal('hide');
        })

        window.maskPlannedOperatingTime = () => {
            var maskBehavior = function(val) {
                return "HZSHZ";
            }

            spOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(maskBehavior.apply({}, arguments), options);
                },
                translation: {
                    'H': {
                        pattern: /\d/,
                        optional: false
                    },
                    'Z': {
                        pattern: /[0-9]/,
                        optional: true
                    },
                    'S': {
                        pattern: /[,-.-:]/,
                        optional: false
                    },
                }
            };

            $("input[data-type='time']").mask(maskBehavior, spOptions);
        };
        maskPlannedOperatingTime();

        window.livewire.on('maskPlannedOperatingTime', () => {
            maskPlannedOperatingTime();
        });

        window.addEventListener('focusErrorInput', event => {
            var $field = '#' + event.detail.field + '_' + event.detail.model_id;
            $($field).focus();
        });

        window.addEventListener('autoFocusInput', event => {
            // $(".editable-input").blur();

            var $field = '#planned_operating_time_' + event.detail.model_id;

            setTimeout(function () {
                $($field).focus();
            },100);
        });

        window.addEventListener('autoFocusOutInput', event => {
            let $field = 'planned_operating_time_' + event.detail.model_id;

            setTimeout(function () {
                $('#' + $field).blur();
            },100);
        });

        var computeDashboardAjax = null;

        window.addEventListener('computeAndStoreAllDashboardWidgets', event => {
            /* To prevent multiple call jQuery ajax with abort. */
            // Livewire.emit('computeAndStoreAllDashboardWidgets'+event.detail);

            computeDashboardAjax = $.ajax({
                type: "GET",
                url: "{{ route('compute-dashboard-widgets-ajax') }}",
                beforeSend : function() {
                    if (computeDashboardAjax != null) {
                        computeDashboardAjax.abort();
                    }
                }
            });
        });
    </script>
@endsection
