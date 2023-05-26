<div wire:key="timer-component-{{ time() }}"
     class="timer-component-style rounded {{ request()->url() }} flex justify-content-center align-items-center {{ !$counter ? 'hide-shadow' : ''}}"
     style="position: fixed;bottom: 10%;right: 60px;z-index: 1031;width: 200px;">

    @if ($counter)
        @if (isset($customer))
            <div class="text-wrap text-center"
                style="background-color: #FFFFFF;border: 2px solid #FF2829;border-top-left-radius: 5px;border-top-right-radius: 5px;color: #FF2829;font-weight: bolder;">
                <span>{{ $customer->customer_name }}</span>
            </div>
        @endif

        <div class="d-flex justify-content-center align-items-center pl-1 pr-1"
             style="@if (is_null($customer)) height: 45px; @endif">
            <div class="timer" id="timer" style="padding: 5px;font-size: 16px;font-weight: bolder;">
                00 : 00 : 00
            </div>

            <button class="btn btn-outline-danger btn-icon" id="nav-stop-timer" wire:click="stopChronosTimerComponent"
                    style="border: 0;">
                <i id="loading-spinner-for-nav-timer-icon-button" style="color: #FFFFFF;font-size: 1.8rem"
                   class="bx bx-stop-circle"></i>
                <div id="loading-spinner-for-nav-timer-button" class="d-none">
                    <div class="la-ball-spin-clockwise la-sm" style="margin-left: 20px;">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </button>
        </div>
    @endif

    @if ($counter)
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function (e) {
                setInterval(() => window.livewire.emit('pollingTimeLogs'), 30000);
            });
        </script>
    @endif
</div>
