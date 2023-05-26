<div class="connection-edit-form">
    <div class="modal-header modal-header-edit-connection box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
        <h5 class="modal-title" id="editConnectionModalLabel">{{ __('locale.Edit Connection') }}</h5>
        <button type="button" wire:click="closedEditConnectionModal" class="close" style="background-color: #c9c9c9" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><strong>&times;</strong></span>
        </button>
    </div>
    <div class="modal-body modal-body-edit-connection">
        <form class="form-group flex" wire:submit.prevent="save">
            @csrf
            <div class="row pt-1" >
                <div class="col">
                    <label for="bdgogid">{{ __('locale.Company Name') }}</label>
                    @if(auth()->user()->hasRole('Admin'))
                        <i wire:click="showCustomerModal" class="float-right cursor-pointer bx bx-plus-circle" title="{{ __('locale.New Customer') }}"></i>
                    @endif
                    <select class="form-control" id="bdgogid" wire:model="bdgogid">
                        @if(!$selectedConnection)
                            <option value="">{{ __('locale.Select') }}</option>
                        @endif
                        @foreach($groups as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('bdgogid') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row pt-1" wire:ignore.self>
                <div class="col">
                    <label for="userid">{{ __('locale.User') }}</label>
                    @if(auth()->user()->hasRole('Admin'))
                        <i wire:click="openUserModal" class="float-right cursor-pointer bx bx-plus-circle" title="{{__('locale.New User')}}"></i>
                    @endif
                    <select id="userid" class="form-control" wire:model="userid">
                        @if(!$selectedConnection)
                            <option value="">{{ __('locale.Select') }}</option>
                        @endif
                        @foreach($users as $key => $value)
                            <option value="{{ $value }}">{{ $key }}</option>
                        @endforeach
                    </select>
                    @error('userid') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row pt-1" wire:ignore.self>
                <div class="col">
                    <label for="cont_id">{{ __('locale.Contact') }}</label>
                    @if($selectedCustomer)
                        <i wire:click="showContactModal" wire:target="showContactModal"
                           class="float-right cursor-pointer bx bx-plus-circle" title="{{ __('locale.New Contact') }}"></i>
                    @endif
                    <select id="cont_id" class="form-control" wire:model="cont_id">
                        <option value="">{{ __('locale.Select') }}</option>
                        @foreach($contacts as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('cont_id') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row pt-1" wire:ignore.self>
                <div class="col-12 col-md-9">
                    <label for="device_id">{{ __('locale.Device') }}</label>
                    @if($selectedCustomer)
                        <i wire:click="showDeviceModal" class="float-right cursor-pointer bx bx-plus-circle" title="{{ __('locale.New Device') }}"></i>
                    @endif
                    <select id="device_id" class="form-control" wire:model="device_id">
                        <option value="">{{ __('locale.Select') }}</option>
                        @foreach($devices as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('device_id') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
                <div class="col-6 col-md-3">
                    <label for="support_session_type">{{ __('locale.Session Type') }}</label>
                    <input disabled type="text" id="support_session_type" class="form-control text-center" wire:model="support_session_type">
                    @error('support_session_type') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row pt-1">
                <div class="width-40-per pl-1">
                    <label for="start_date">{{ __('locale.Start') }}</label>
                    <input type="text" id="start_date" class="form-control date_time" wire:model.lazy="start_date" placeholder="dd.mm.yyyy hh:hh">
                    @error('start_date') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
                <div class="width-40-per pl-1">
                    <label for="end_date">{{ __('locale.End') }}</label>
                    <input type="text" id="end_date" class="form-control date_time" wire:model.lazy="end_date" placeholder="dd.mm.yyyy hh:hh">
                    @error('end_date') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
                <div class="width-20-per text-center">
                    <label for="duration">{{ __('locale.Duration') }}</label>
                    <p class="pt-1"><strong>{{ $duration }}m</strong></p>
                </div>
            </div>
            <div class="row content-tariff-invoice">
                <div class="col">
                    <label for="select-tariff">{{ __('locale.Tariff Name') }}</label>
                    <select class="form-control" id="select-tariff" wire:model="tariff_id">
                        <option value="">{{ __('locale.Select') }}</option>
                        @foreach($tariffs as $tariff)
                            <option value="{{ $tariff->id }}">{{ $tariff->tariff_name }}</option>
                        @endforeach
                    </select>
                    @error('tariff_id') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
                <div class="col">
                    <label for="billing_state">{{ __('locale.Invoice') }}</label>
                    <select id="billing_state" class="form-control" wire:model="billing_state">
                        <option value="">{{ __('locale.Select') }}</option>
                        <option value="Bill">{{ __('locale.Charge') }}</option>
                        <option value="DoNotBill">{{ __('locale.Do Not Charge') }}</option>
                        <option value="Hide">{{ __('locale.Hide') }}</option>
                    </select>
                    @error('billing_state') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row content-notes">
                <div class="col">
                    <fieldset class="form-label-group">
                        <textarea type="text" id="activity_report" class="form-control" wire:model="activity_report" style="height: 120px;" placeholder="{{ __('locale.Report Notes') }}"></textarea>
                        <label for="activity_report">{{ __('locale.Report Notes') }}</label>
                    </fieldset>
                    @error('activity_report') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
                <div class="col">
                    <fieldset class="form-label-group">
                        <textarea type="text" id="notes" class="form-control" wire:model="notes" style="height: 120px;" placeholder="{{ __('locale.Job Description') }}"></textarea>
                        <label for="notes">{{ __('locale.Job Description') }}</label>
                    </fieldset>
                    @error('notes') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center pl-0 pr-0">
                <div class="col">
                </div>
                <div class="col d-flex pr-0">
                    <button style="margin-right: 3px;" type="submit" class="btn btn-primary">{{ __('locale.Save') }}</button>
                    <button wire:click="closedEditConnectionModal" class="btn btn-secondary" data-dismiss="modal">{{ __('locale.Cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
