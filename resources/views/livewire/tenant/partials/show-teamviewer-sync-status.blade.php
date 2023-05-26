<div class="show-anydesk-sync-status">
    <div class="text-status">
        @if(!$connected)
            <span>{{__('locale.Teambilling has no connection to Teamviewer')}}</span>
        @elseif($connected && $is_sync_disabled)
            <span>{{__('locale.Teamviewer Data Synchronization is running')}}</span>
        @endif
    </div>
    <div class="icon-status">
        @if(!$connected)
            <i class='bx bx-error-alt' data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="{{__('locale.Teambilling has no connection to Teamviewer')}}"></i>
        @elseif($connected && $is_sync_disabled)
            <i class='bx bx-sync' data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="{{__('locale.Teamviewer Data Synchronization is running')}}"></i>
        @endif
    </div>
</div>
