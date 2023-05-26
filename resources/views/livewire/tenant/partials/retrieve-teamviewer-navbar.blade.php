<div>
    <ul class="nav bookmark-icons">
        <li class="nav-item d-lg-block ml-2">
            <div wire:key="navbar-loading-container" data-bs-toggle="tooltip" data-bs-placement="left"
                 data-bs-original-title="{{$sync_button_message}}"
                 data-bs-custom-class="tooltip-secondary" class="{{$is_sync_disabled ? 'tv-sync-progress' : ''}}"
                 id="tv_sync">
                <button
                    {{ (!$connected) || ($is_sync_disabled) ? 'disabled' : '' }}
                    class="btn btn-icon tv-sync-status-btn {{ $connected && $is_sync_disabled ? 'sync-running' : '' }}"
                    style="padding-top: 5px;" wire:click="retrieveFromAPI" wire:loading.attr="disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-refresh-ccw">
                        <polyline points="1 4 1 10 7 10"></polyline>
                        <polyline points="23 20 23 14 17 14"></polyline>
                        <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                    </svg>
                </button>
            </div>
        </li>
        <li class="nav-item d-none d-lg-block">
            <a class="btn btn-icon" href="{{route('app.todo')}}"
               title="{{ __('locale.Todo') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-list">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
            </a>
        </li>
    </ul>
</div>
