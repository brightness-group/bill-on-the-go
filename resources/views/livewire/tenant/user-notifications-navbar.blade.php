<div wire:ignore.self>

    <a class="nav-link nav-link-label" href="#" data-toggle="dropdown" > {{--wire:poll.60000ms--}}
        <i class="ficon bx bx-bell bx-tada bx-flip-horizontal"></i>
        <span class="badge badge-pill badge-danger badge-up">{{ $unreadCount ? $unreadCount : '' }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
        <li class="dropdown-menu-header">
            <div class="dropdown-header px-1 py-75 d-flex justify-content-between"><span class="notification-title">
                    {{ __('locale.new Notification',['count'=>$unreadCount]) }}</span>
                @if($unreadCount)
                    <span class="text-bold-400 cursor-pointer" wire:click="markAllAsRead">{{ __('locale.Mark all as read') }}</span>
                @endif
            </div>
        </li>
        <li class="scrollable-container media-list ps">
            @if($unreadCount)
                <livewire:tenant.partials.notification-navbar :notifications="$unreadNotifications" wire:key="unreadNotifications" />
            @endif
            @if($readCount)
                <livewire:tenant.partials.notification-navbar :notifications="$readNotifications" wire:key="readNotifications" />
            @endif
        </li>
        @if($counNotifications)
            <li class="dropdown-menu-footer"><a class="dropdown-item p-50 text-primary justify-content-center" href="javascript:void(0)">{{ __('locale.Read all notifications') }}</a></li>
        @endif
    </ul>
</div>
