{{-- summarize-customers --}}
<div class="modal-dialog">
    <div class="modal-content">
        <div class="summarize-customers">
            <div class="modal-header modal-header-summarize-customers box-shadow-1"
                 style="text-shadow: 1px 1px 2px #7DA0B1">
                <h5 class="modal-title" id="summarizeCustomersModalTitle">{{ __('locale.Summarize customers') }}</h5>
                <button type="button" wire:click="closeSummarizeCustomersModal" class="close"
                        style="background-color: #c9c9c9"
                        data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><strong>&times;</strong></span>
                </button>
            </div>
            <div class="modal-body modal-body-summarize-customers">
                <p>
                    {{ __('locale.You want to summarize the following customers:') }}
                </p>
                <p>
                    {{ __('locale.Please select which of the selected customers should become the main customer?') }}
                </p>

                <form class="form-group flex" wire:submit.prevent="save">
                    @csrf
                    <div class="row pt-1">
                        <div class="col">
                            <label for="company_id">{{ __('locale.Company Name') }}</label>
                            <select class="form-control" id="company_id" wire:model="company_id">
                                <option value="">{{ __('locale.Select') }}</option>
                                @foreach($selectedCustomers as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('company_id') <span class="error" style="color: red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center pl-0 pr-0">
                        <div class="col">
                        </div>
                        <div class="col d-flex pr-0">
                            <button style="margin-right: 3px;"
                                    {{ !auth()->user()->hasRole('Admin') ? 'disabled' : '' }} type="submit"
                                    class="btn btn-primary">{{ __('locale.Save') }}</button>
                            <button type="button" wire:click="closeSummarizeCustomersModal" class="btn btn-secondary"
                                    data-dismiss="modal">{{ __('locale.Cancel') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
