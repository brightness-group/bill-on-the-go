<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form>
            <div class="modal-header">
                <h5 class="modal-title" id="addNewCustomerModalLabel">{{ __('locale.Add Customer') }}</h5>
                <button type="button" wire:click="closeCustomerModal" class="btn-close"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @csrf
                    <div class="col-md-8 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="customer_name">{{ __('locale.Customer Name') }}</label>
                            <input id="customer_name" type="text" class="form-control"
                                   wire:model="customer_name">
                            @error('customer_name') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="planned_operating_time">{{ __('locale.Planned Operating Time') }}</label>
                            <input id="planned_operating_time" type="text" data-type="time"
                                   class="form-control"
                                   wire:model="planned_operating_time">
                            @error('planned_operating_time') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="save" style="margin-right: 3px;" type="button"
                        class="btn btn-dark">{{ __('locale.Save') }}</button>
                <button type="button" class="btn btn-outline-secondary"
                        wire:click="closeCustomerModal">{{ __('locale.Cancel') }}</button>
            </div>
        </form>
    </div>
</div>

