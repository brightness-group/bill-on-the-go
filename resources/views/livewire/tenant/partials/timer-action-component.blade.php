<div wire:key="timer-action-component-{{ time() }}" class="ms-5">
    @if(!$this->counterStarted)
        {{--@php
            // todo: update based on print view.
            $printView = false;
        @endphp--}}

        {{-- start timer button --}}
        <button type="button" class="btn btn-outline-secondary btn-sm start-crono-btn">
            <span><i class="bx bx-play-circle me-1" style="color: darkgreen;"></i></span>
            <span><strong>{{ __('locale.Launch') }}</strong></span>
            <div class="start-cronos-loader d-none">
                <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm la-dark" style="margin-left: 20px;"></x-loading.ball-spin-clockwise>
            </div>
        </button>

        <button class="btn btn-outline-secondary chrono_actions_button btn-sm me-1 d-none stop-crono-btn" id="chrono_loading_manual_modal_button">
            <div class="d-flex justify-content-center align-items-center" wire:key="chrono_loading_manual_modal_button-{{ time() }}">
                <span><i class="bx bx-stop-circle me-1" style="color: red"></i></span>
                <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm la-dark ms-3"></x-loading.ball-spin-clockwise>
            </div>
        </button>
    @else
        {{-- stop timer button --}}
        <button style="margin-right: 3px;padding: 0.22rem 0.8rem;border-radius: 5px;border: 2px #B0BED9 solid;font-size: 12px;color: #7DA0B1;text-align: center;"
        title="{{ __('locale.Stop') }}"
                class="btn btn-light-secondary btn-sm chrono_actions_button stop-crono-btn" {{--wire:click="stopChronosAction"--}} id="chrono_actions_button">
            <div class="d-flex justify-content-center align-items-center" wire:key="stop-button-chronos-{{ time() }}">
                <span><i class="bx bx-stop-circle" style="color: red;margin-right: 3px;"></i></span>
                <span wire:loading.remove wire:target="stopChronos"><strong>{{ __('locale.Stop') }}</strong></span>
                <div wire:loading wire:target="stopChronos">
                    <x-loading.ball-spin-clockwise class="la-ball-spin-clockwise la-sm la-dark ms-3"></x-loading.ball-spin-clockwise>
                </div>
            </div>
        </button>
    @endif
</div>
