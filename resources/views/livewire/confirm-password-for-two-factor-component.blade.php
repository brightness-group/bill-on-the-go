<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">
            {{ __('locale.Two Factor Authentication') }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-2">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ session('message') }}
                </div>
            @endif
        </div>
        <div class="mb-2">
            <label class="form-label">{{ __('locale.Confirm your Password') }}</label>
            <input type="password" class="form-control" wire:model="confirmablePassword">
            @if($errors->has('confirmablePassword'))
                <p style="color: #ff0000">{{ $errors->first('confirmablePassword') }}</p>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-dark" wire:click="confirmPassword">{{ __('locale.Confirm') }}</button>
        <button class="btn btn-outline-secondary" data-dismiss="modal"
                wire:click="stopConfirmingPassword">{{ __('locale.Cancel') }}</button>
    </div>
</div>
