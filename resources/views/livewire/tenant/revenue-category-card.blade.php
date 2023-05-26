<div class="card h-100">
    <div class="card-header d-sm-flex justify-content-between">
        <h5 class="card-title">{{__('locale.Revenue by category')}}</h5>

        <ul class="nav nav-tabs custom-dashboard-tabs" role="tablist">
            <li class="nav-item text-center">
                <button class="nav-link {{ $tabSelected == 'current_month' ? 'active' : '' }}" id="current_month-tab"
                    aria-controls="current_month"
                    wire:click="setSelectedTab('current_month')" aria-selected="true">
                    <span class="align-middle">{{ __('locale.Current Month') }}</span>
                </button>
            </li>
            <li class="nav-item text-center">
                <button class="nav-link {{ $tabSelected == 'current_year' ? 'active' : '' }}" id="current_year-tab"
                    aria-controls="current_year"
                    wire:click="setSelectedTab('current_year')" aria-selected="true">
                    <span class="align-middle">{{ __('locale.Current Year') }}</span>
                </button>
            </li>
            <li class="nav-item text-center">
                <button class="nav-link {{ $tabSelected == 'last_month' ? 'active' : '' }}" id="last_month-tab"
                    aria-controls="last_month"
                    wire:click="setSelectedTab('last_month')" aria-selected="false">
                    <span class="align-middle">{{ __('locale.Last Month') }}</span>
                </button>
            </li>
            <li class="nav-item text-center">
                <button class="nav-link {{ $tabSelected == 'last_quarter' ? 'active' : '' }}" id="last_quarter-tab"
                    aria-controls="last_quarter"
                    wire:click="setSelectedTab('last_quarter')" aria-selected="false">
                    <span class="align-middle">{{ __('locale.Last Quarter') }}</span>
                </button>
            </li>
        </ul>
    </div>
    <div class="card-content">
        <div class="card-body pt-1">
            <div class="d-flex activity-content">
                <div class="avatar-sm flewx-shrink-0 me-3 mt-2">
                    <img data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Teamviewer') }}</span>" width="24" height="24" src="{{ mix('assets/images/ico/icon_anydesk_64.png') }}" alt="tv-icon" />
                </div>
                <div class="activity-progress flex-grow-1">
                    <small class="text-light-black d-inline-block mb-50">{{__('locale.Teamviewer')}}</small>
                    <small class="float-end pe-1">
                        {{!empty($anydesk_category['total_price']) ? $anydesk_category['total_price'].'€' : 0}}
                    </small>
                    <div class="progress progress-bar-light progress-sm">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{$anydesk_category['percentage']}}" style="width:{{$anydesk_category['percentage']}}%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex activity-content">
                <div class="avatar avatar-sm flewx-shrink-0 me-3 mt-2">
                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Email') }}</span>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg>
                </div>
                <div class="activity-progress flex-grow-1">
                    <small class="text-light-black d-inline-block mb-50">{{__('locale.Email')}}</small>
                    <small class="float-end pe-1">
                        {{!empty($email_category['total_price']) ? $email_category['total_price'].'€' : 0}}
                    </small>
                    <div class="progress progress-bar-warning progress-sm">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{$email_category['percentage']}}" style="width:{{$email_category['percentage']}}%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex activity-content">
                <div class="avatar avatar-sm flewx-shrink-0 me-3 mt-2">
                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Phone Call') }}</span>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <div class="activity-progress flex-grow-1">
                    <small class="text-light-black d-inline-block mb-50">{{__('locale.Phone Call')}}</small>
                    <small class="float-end pe-1">
                        {{!empty($phone_call_category['total_price']) ? $phone_call_category['total_price'].'€' : 0}}
                    </small>
                    <div class="progress progress-bar-success progress-sm">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{$phone_call_category['percentage']}}" style="width:{{$phone_call_category['percentage']}}%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex activity-content">
                <div class="avatar avatar-sm flewx-shrink-0 me-3 mt-2">
                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Video Call') }}</span>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                </div>
                <div class="activity-progress flex-grow-1">
                    <small class="text-light-black d-inline-block mb-50">{{__('locale.Video Call')}}</small>
                    <small class="float-end pe-1">
                        {{!empty($video_call_category['total_price']) ? $video_call_category['total_price'].'€' : 0}}
                    </small>
                    <div class="progress progress-bar-danger progress-sm">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{$video_call_category['percentage']}}" style="width:{{$video_call_category['percentage']}}%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex activity-content">
                <div class="avatar avatar-sm flewx-shrink-0 me-3 mt-2">
                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.On Site') }}</span>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div class="activity-progress flex-grow-1">
                    <small class="text-light-black d-inline-block mb-50">{{__('locale.On Site')}}</small>
                    <small class="float-end pe-1">
                        {{!empty($onsite_category['total_price']) ? $onsite_category['total_price'].'€' : 0}}
                    </small>
                    <div class="progress progress-bar-primary progress-sm">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{$onsite_category['percentage']}}" style="width:{{$onsite_category['percentage']}}%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex mb-75">
                <div class="avatar avatar-sm flewx-shrink-0 me-3 mt-2">
                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.VPN') }}</span>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <div class="activity-progress flex-grow-1">
                    <small class="text-light-black d-inline-block mb-50">{{__('locale.VPN')}}</small>
                    <small class="float-end pe-1">
                        {{!empty($vpn_category['total_price']) ? $vpn_category['total_price'].'€' : 0}}
                    </small>
                    <div class="progress progress-bar-info progress-sm">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{$vpn_category['percentage']}}" style="width:{{$vpn_category['percentage']}}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
