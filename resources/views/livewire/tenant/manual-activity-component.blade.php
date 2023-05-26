<div class="connection-edit-form">
    <div class="modal-header modal-header-edit-connection box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
        @if(isset($selectedConnection))
            <h5 class="modal-title" id="editConnectionModalLabel">{{ __('locale.Edit manual activity') }}</h5>
        @else
            <h5 class="modal-title" id="editConnectionModalLabel">{{ __('locale.Add a new manual activity') }}</h5>
        @endif
        <button type="button" wire:click="closeManualActivityModal" class="close" style="background-color: #c9c9c9;width: 2rem;height: 2rem;" aria-label="Close">
            <div class="d-flex justify-content-center align-content-center">
                <span aria-hidden="true"><strong>&times;</strong></span>
            </div>
        </button>
    </div>
    <div class="modal-body modal-body-edit-connection">
        <form class="form-group flex pt-1">
            @csrf
            <input type="hidden" wire:model="timezone" id="timezone">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="form-group" wire:key="select-customer-element-{{ time() }}">
                        <label for="bdgogid_manual_activity">{{ __('locale.Customer Name') }}</label>
                        @if(auth()->user()->hasRole('Admin'))
                            <i wire:click="openCustomerModal" class="float-right cursor-pointer bx bx-plus-circle" title="{{__('locale.New Customer')}}"></i>
                        @endif
                        <select class="form-control" id="bdgogid_manual_activity" wire:model="bdgogid" wire:change="$emit('customerChange',$event.target.value)" style="font-size: 12px;">
                            <option value="">{{ __('locale.Select') }}</option>
                            @foreach($groups as $key => $value)
                                <option class="test-123" value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('bdgogid') <span class="error" style="color: red">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="cont_id">{{ __('locale.Contact') }}</label>
                        @if($selectedCustomer)
                            <i wire:click="showContactModal" wire:target="showContactModal"
                               class="float-right cursor-pointer bx bx-plus-circle" title="{{ __('locale.New Contact') }}"></i>
                        @endif
                        <select id="cont_id" class="form-control" wire:model="cont_id" style="font-size: 12px;">
                            <option value="">{{ __('locale.Select') }}</option>
                            @foreach($contacts as $item)
                                <option value="{{ $item->id }}">{{ $item->firstname }} {{ $item->lastname }}</option>
                            @endforeach
                        </select>
                        @error('cont_id') <span class="error" style="color: red">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" wire:ignore.self>
                        <label for="userid_manual_activity">{{ __('locale.User') }}</label>
                        @if(auth()->user()->hasRole('Admin'))
                            <i wire:click="openUserModal" class="float-right cursor-pointer bx bx-plus-circle" title="{{ __('locale.New User') }}"></i>
                        @endif
                        <select id="userid_manual_activity" class="form-control" wire:model="userid" style="font-size: 12px;">
                            <option value="">{{ __('locale.Select') }}</option>
                            @foreach($users as $key => $value)
                                <option value="{{ $value }}">{{ $key }}</option>
                            @endforeach
                        </select>
                        @error('userid') <span class="error" style="color: red">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" wire:ignore.self>
                        <label for="topic_manual_activity">{{ __('locale.Topic') }}</label>
                        <input type="text" class="form-control" id="topic_manual_activity" wire:model="topic" style="font-size: 12px;">
                        @error('topic') <span class="error" style="color: red">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="contact_type_manual_activity">{{ __('locale.Contact Type') }}</label>
                        <div class="row" id="contact_type_manual_activity">
                            <div class="col d-flex align-items-center">
                                <button type="button" class="btn btn-outline-primary btn-icon @if($contact_type != null && $contact_type == 1) border-primary @endif"
                                        style="border-color: white;" wire:click="contactTypeChange(1)">
                                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Email') }}</span>" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-icon @if($contact_type != null && $contact_type == 2) border-primary @endif"
                                        style="margin-left: 5px;border-color: white;" wire:click="contactTypeChange(2)">
                                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Phone Call') }}</span>" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-icon @if($contact_type != null && $contact_type == 3) border-primary @endif"
                                        style="margin-left: 5px;border-color: white;" wire:click="contactTypeChange(3)">
                                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.Video Call') }}</span>" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-icon @if($contact_type != null && $contact_type == 4) border-primary @endif"
                                        style="margin-left: 5px;border-color: white;" wire:click="contactTypeChange(4)">
                                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.On Site') }}</span>" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-icon @if($contact_type != null && $contact_type == 5) border-primary @endif"
                                        style="margin-left: 5px;border-color: white;" wire:click="contactTypeChange(5)">
                                    <svg data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="" data-bs-original-title="<span>{{ __('locale.VPN') }}</span>" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" wire:ignore.self>
                                <label for="device_id_manual_activity">{{ __('locale.Device') }}</label>
                                @if($selectedCustomer)
                                    <i wire:click="showDeviceModal" class="float-right cursor-pointer bx bx-plus-circle" title="{{ __('locale.New Device') }}"></i>
                                @endif
                                <select id="device_id_manual_activity" class="form-control" wire:model="device_id" style="font-size: 12px;">
                                    <option value="">{{ __('locale.Select') }}</option>
                                    @foreach($devices as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('device_id') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="billing_state_manual_activity">{{ __('locale.Invoice') }}</label>
                                <select id="billing_state_manual_activity" class="form-control" wire:model="billing_state" style="font-size: 12px;">
                                    <option value="Bill">{{ __('locale.Charge') }}</option>
                                    <option value="DoNotBill">{{ __('locale.Do Not Charge') }}</option>
                                    <option value="Hide">{{ __('locale.Hide') }}</option>
                                </select>
                                @error('billing_state') <span class="error" style="color: red;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="start_date_manual_activity">{{ __('locale.Start Date') }}</label>
                            <fieldset class="form-group position-relative has-icon-left display-inline-block" id="start_date_manual_activity">
                                <input wire:model="start_date" type="text" class="form-control date_time_manual_activity" placeholder="dd.mm.yyyy"
                                       onchange="this.dispatchEvent(new InputEvent('input'))" style="font-size: 12px;">
                                <div class="form-control-position">
                                    <i class="bx bx-calendar"></i>
                                </div>
                                @error('start_date') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </fieldset>
                        </div>
                        <div class="col-md-5">
                            <label for="end_date_manual_activity">{{ __('locale.End Date') }}</label>
                            <fieldset class="form-group position-relative has-icon-left display-inline-block" id="end_date_manual_activity">
                                <input wire:model="end_date" type="text" class="form-control date_time_manual_activity" placeholder="dd.mm.yyyy"
                                       onchange="this.dispatchEvent(new InputEvent('input'))" style="font-size: 12px;">
                                <div class="form-control-position">
                                    <i class="bx bx-calendar"></i>
                                </div>
                                @error('end_date') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </fieldset>
                        </div>
                        <div class="col-md-2 text-center pl-0 d-none">
                            <label for="duration">{{ __('locale.Duration') }}</label>
                            <p class="pt-1"><strong>{{ $duration }}m</strong></p>
                        </div>
                    </div>
                    <div class="row pt-1 pb-1">
                        <div class="col-md-5">
                            <label for="start_time_manual_activity">{{ __('locale.Start Time') }}</label>
                            <input type="text" id="start_time_manual_activity" class="form-control manual_activity_time" wire:model="start_time" placeholder="00:00:00" style="font-size: 12px;">
                            @error('start_time') <span class="error" style="color: red">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-5">
                            <label for="end_time_manual_activity">{{ __('locale.End Time') }}</label>
                            <input type="text" id="end_time_manual_activity" class="form-control manual_activity_time" wire:model="end_time" placeholder="23:59:59" style="font-size: 12px;">
                            @error('end_time') <span class="error" style="color: red">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-2 text-center pl-0 align-items-center">
                            <label for="duration">{{ __('locale.Duration') }}</label>
                            <p class="mb-0" style="font-size: 12px;margin-top: 5px;"><strong>{{ $duration }}m</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 pt-1">
                    <div class="form-group">
                        <fieldset class="form-label-group">
                            <textarea type="text" id="note_manual_activity" class="form-control" wire:model="notes" style="height: 120px;font-size: 12px;" placeholder="{{ __('locale.Job Description') }}"></textarea>
                            <label for="notes">{{ __('locale.Job Description') }}</label>
                        </fieldset>
                        @error('notes') <span class="error" style="color: red">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <fieldset class="form-label-group">
                            <textarea type="text" id="activity_report_manual_activity" class="form-control" wire:model="activity_report" style="height: 120px;font-size: 12px;" placeholder="{{ __('locale.Report Notes') }}"></textarea>
                            <label for="activity_report">{{ __('locale.Report Notes') }}</label>
                        </fieldset>
                        @error('activity_report') <span class="error" style="color: red">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer d-flex justify-content-between">
        <div class="col">
        </div>
        <div class="col ml-auto d-flex justify-content-end">
            <button wire:click="save" style="margin-right: 3px;" type="submit" class="btn btn-sm btn-primary">{{ __('locale.Save') }}</button>
            <button class="btn btn-sm btn-secondary" wire:click="closeManualActivityModal">{{ __('locale.Cancel') }}</button>
        </div>
    </div>
</div>
