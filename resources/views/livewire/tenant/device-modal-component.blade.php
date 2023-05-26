<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form>
            <div class="modal-header">
                <h5 class="modal-title" id="addNewDeviceModalLabel">{{ __('locale.Add Device') }}</h5>
                <button type="button" wire:click="closeDeviceModal" class="btn-close"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @csrf
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="alias">{{ __('locale.Alias') }}</label>
                            <input type="text" class="form-control" id="alias" wire:model="alias">
                            @error('alias') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="description">{{ __('locale.Description') }}</label>
                            <input type="text" class="form-control" id="description" wire:model="description">
                            @error('description') <span
                                class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="save" style="margin-right: 3px;" type="button"
                        class="btn btn-dark">{{ __('locale.Save') }}</button>
                <button type="button" class="btn btn-outline-secondary" wire:click="closeDeviceModal"
                        data-dismiss="modal">{{ __('locale.Cancel') }}</button>
            </div>
        </form>
    </div>
</div>
