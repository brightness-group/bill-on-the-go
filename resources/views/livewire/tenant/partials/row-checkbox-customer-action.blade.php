<div>
    <fieldset>
        <div class="checkbox" wire:loading.remove wire:target="changeCustomerStatus">
            <input {{ !auth()->user()->hasRole('Admin') ? 'disabled' : '' }} type="checkbox" wire:click="changeCustomerStatus" id="checkbox-action-trash-{{ $customer->id }}" class="checkbox-input"
                   style="border: 4px #495057 solid;" {{ $customer->trashed() ? 'checked' : '' }}>
            <label for="checkbox-action-trash-{{ $customer->id }}"></label>
        </div>
        <div wire:loading wire:target="changeCustomerStatus">
            <div class="la-ball-spin-clockwise la-sm" style="color: #495057;">
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
    </fieldset>
</div>
