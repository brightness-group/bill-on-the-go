<div>
    <div class="modal-body">
        <div class="mb-2">
            <label class="form-label">{{ __('locale.Subdomain')}}</label>
            <input id="subdomain" type="text" class="form-control flex-row"
                   wire:model="subdomain">
            @error('subdomain') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
        </div>
        <div class="mb-2">
            <label class="form-label">{{ __('locale.Description')}}</label>
            <textarea type="text" id="description" class="form-control flex-row"
                      wire:model="description"></textarea>
            @error('description') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
        </div>
        <div class="mb-2">
            <label class="form-label">{{ __('locale.Target')}}</label>
            <input id="target" type="text" class="form-control flex-row"
                   wire:model="target">
            @error('target') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="modal-footer">
        @if($modelId)
            <button class="btn btn-dark" wire:click="store" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="store">{{ __('locale.Save') }}</span>
                <span style="width: 40px;margin: 0;padding-left: 10px;" wire:loading wire:target="store">
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
            <button class="btn btn-dark" wire:click="store" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="store">{{ __('locale.Create') }}</span>
                <span style="width: 40px;margin: 0;padding-left: 10px;" wire:loading wire:target="store">
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
