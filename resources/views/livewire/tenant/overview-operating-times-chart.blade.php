<div class="border-right">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ __('locale.Overview operating times') }}</h5>
        </div>
        <div class="card-body" style="padding: 10px;">
            <div class="d-flex justify-content-between align-items-center" style="padding-left: 10px;">
                <ul class="nav nav-pills custom-dashboard-tabs ml-10" role="tablist">
                    <li class="nav-item text-center">
                        <button type="button"
                                class="btn btn-sm {{ $duration_months == 1 ? 'btn-dark' : 'btn-outline-dark' }} me-1"
                                wire:click="updateTimeDuration(1)">{{ __('locale.Month') }}</button>
                    </li>

                    <li class="nav-item text-center">
                        <button type="button"
                                class="btn btn-sm {{ $duration_months == 12 ? 'btn-dark' : 'btn-outline-dark' }}"
                                wire:click="updateTimeDuration(12)">{{ __('locale.Quarter') }}</button>
                    </li>
                </ul>

                @if ($duration_months == 12)
                    <select class="form-control" style="width: auto;" wire:model="year">
                        <option value="1" selected="true">{{ __('locale.All') }}</option>

                        @if (!empty($filters['years']))
                            @foreach ($filters['years'] as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        @endif
                    </select>
                @endif

                @if ($duration_months == 1)
                    <select class="form-control" style="width: auto;" wire:model="quarter">
                        <option value="1" selected="true">{{ __('locale.All') }}</option>

                        @if (!empty($filters['quarters']))
                            @foreach ($filters['quarters'] as $quarter => $timeObj)
                                <option value="{{ $quarter }}">Q{{ $quarter }}</option>
                            @endforeach
                        @endif
                    </select>
                @endif
            </div>

            <br />

            <div class="tab-content pl-0" style="padding: 0px !important;">
                <div id="overview-operating-times-chart"></div>
            </div>
        </div>
    </div>
</div>
