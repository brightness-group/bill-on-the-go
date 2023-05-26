<div class="card h-100 widget-state-multi-radial">
    <div class="card-header d-sm-flex justify-content-between">
        <h5 class="card-title">Statistics</h5>

        <ul class="nav nav-pills custom-dashboard-tabs ml-10" role="tablist" style="float: right;">
            <li class="nav-item text-center">
                <button type="button"
                        class="btn btn-sm {{ $durationMonths == 1 ? 'btn-dark' : 'btn-outline-dark' }} me-1"
                        wire:click="renderStatisticsRadialChart(1)">{{ __('locale.Last Month') }}</button>
            </li>

            <li class="nav-item text-center">
                <button type="button"
                        class="btn btn-sm {{ $durationMonths == 12 ? 'btn-dark' : 'btn-outline-dark' }}"
                        wire:click="renderStatisticsRadialChart(12)">{{ __('locale.Last Quarter') }}</button>
            </li>
        </ul>

        <ul class="nav nav-tabs mt-sm-0 mt-50 mb-0" role="tablist">
            <li class="nav-item">
                <button class="nav-link {{$contact_type == 'email' ? 'active' : ''}}" id="email-tab"
                        wire:click="$set('contact_type','email')" aria-controls="email"
                        role="tab" aria-selected="true">
                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Email') }}</span>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{$contact_type == 'phonecall' ? 'active' : ''}}" id="phone-call-tab"
                        wire:click="$set('contact_type','phonecall')" aria-controls="phone-call"
                        role="tab" aria-selected="false">
                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Phone Call') }}</span>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{$contact_type == 'videocall' ? 'active' : ''}}" id="video-call-tab"
                        wire:click="$set('contact_type','videocall')" aria-controls="video-call"
                        role="tab" aria-selected="false">
                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Video Call') }}</span>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{$contact_type == 'onsite' ? 'active' : ''}}" id="localization-tab"
                        wire:click="$set('contact_type','onsite')"
                        aria-controls="localization"
                        role="tab" aria-selected="false">
                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.On Site') }}</span>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{$contact_type == 'vpn' ? 'active' : ''}}" id="vpn-globe-tab"
                        wire:click="$set('contact_type','vpn')" aria-controls="vpn-globe"
                        role="tab" aria-selected="false">
                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.VPN') }}</span>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{$contact_type == 'anydesk' ? 'active' : ''}}" id="anydesk-tab"
                        wire:click="$set('contact_type','anydesk')" aria-controls="anydesk"
                        role="tab" aria-selected="false">
                    <img data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Teamviewer') }}</span>" width="24" height="24" src="{{ mix('assets/images/ico/icon_anydesk_64.png') }}" alt="tv-icon" />
                </button>
            </li>
        </ul>
    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-2 text-center">
                        <h6>{{__('locale.Top 5 customers')}}</h6>
                    </div>

                    <p class="text-center d-none" id="no-data">{{__('locale.No data available')}}</p>

                    <div id="statistics-multi-radial-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
