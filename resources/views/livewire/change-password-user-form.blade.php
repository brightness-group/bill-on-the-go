<div>
    <div class="mb-2">
        <label class="form-label">{{ __('locale.Old Password')}}</label>
        <input id="current_password" type="text" class="form-control flex-row" wire:model="current_password">
        @error('current_password') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
    </div>
    <div class="mb-2">
        <label class="form-label">{{ __('locale.New Password')}}</label>
        <input id="password" type="text" class="form-control flex-row" wire:model="password">
        @error('password') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
    </div>
    <div class="mb-2">
        <label class="form-label">{{ __('locale.Confirm new Password')}}</label>
        <input id="password_confirmation" type="text" class="form-control flex-row" wire:model="password_confirmation">
        @error('password_confirmation') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
    </div>
    <div class="mb-2">
        @if($userId)
            <button class="btn btn-dark" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">{{ __('locale.Save') }}</span>
                <span style="width: 40px;margin: 0;padding-left: 10px;" wire:loading wire:target="save">
                        <div style="color: black;" class="la-line-scale la-sm">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </span>
            </button>
        @else
            <button class="btn btn-dark" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">{{ __('locale.Create') }}</span>
                <span style="width: 40px;margin: 0;padding-left: 10px;" wire:loading wire:target="save">
                    <div style="color: black;" class="la-line-scale la-sm">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </span>
            </button>
        @endif
        <button class="btn btn-outline-secondary" wire:click="closeFormModal">{{ __('locale.Cancel') }}</button>
    </div>
</div>





