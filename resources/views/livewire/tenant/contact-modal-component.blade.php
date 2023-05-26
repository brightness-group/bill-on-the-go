<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form>
            <div class="modal-header">
                <h5 class="modal-title" id="addNewContactModalLabel">{{ __('locale.Add Contact') }}</h5>
                <button type="button" wire:click="closeContactModal" class="btn-close"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label" for="salutation">{{ __('locale.Mr/Mrs') }}</label>
                        <select class="form-control" id="salutation" wire:model="salutation"
                                wire:key="select-salutation-element-{{ time() }}" style="width: 80px;">
                            <option value="null" selected></option>
                            <option value="0">{{ __('locale.Mrs') }}</option>
                            <option value="1">{{ __('locale.Mr') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="contact_firstname">{{ __('locale.First Name') }}</label>
                            <input type="text" class="form-control" id="contact_firstname" wire:model="firstname">
                            @error('firstname') <span
                                class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="contact_lastname">{{ __('locale.Last Name') }}</label>
                            <input type="text" class="form-control" id="contact_lastname" wire:model="lastname">
                            @error('lastname') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="contact_department">{{ __('locale.Department') }}</label>
                            <input type="text" class="form-control" id="contact_department"
                                   wire:model="c_department">
                            @error('c_department') <span
                                class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="contact_function">{{ __('locale.Function') }}</label>
                            <input type="text" class="form-control" id="contact_function" wire:model="c_function">
                            @error('c_function') <span
                                class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="contact_b_number">{{ __('locale.Business') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                <input type="text" class="form-control" id="contact_b_number" wire:model="b_number">
                            </div>
                            @error('b_number') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="contact_m_number">{{ __('locale.Mobile') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                <input type="text" class="form-control" id="contact_m_number" wire:model="m_number">
                            </div>
                            @error('m_number') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="contact_h_number">{{ __('locale.Home') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                <input type="text" class="form-control" id="contact_h_number" wire:model="h_number">
                            </div>
                            @error('h_number') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="contact_s_email">{{ __('locale.Service Email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-mail-send"></i></span>
                                <input type="text" class="form-control" id="contact_s_email" wire:model="s_email">
                            </div>
                            @error('s_email') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="contact_p_email">{{ __('locale.Private Email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-mail-send"></i></span>
                                <input type="text" class="form-control" id="contact_p_email" wire:model="p_email">
                            </div>
                            @error('p_email') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="mb-2">
                            <label
                                for="{{$actionType ? $actionType.'contact_devices' : 'contact_devices'}}">{{ __('locale.Select Device') }}</label>
                            <select
                                {{ $selectedCustomer ? '' : 'disabled' }} id="{{$actionType ? $actionType.'contact_devices' : 'contact_devices'}}"
                                wire:model="selectedDevice" class="form-control contact_devices_multiple"
                                multiple="multiple" data-placeholder="{{ __('locale.Select') }}"
                                style="width: 100%;">
                                @if($selectedCustomer && $devices)
                                    @foreach($devices as $device)
                                        <option value="{{ $device->id }}">{{ $device->alias }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="save" style="margin-right: 3px;" type="button"
                        class="btn btn-dark">{{ __('locale.Save') }}</button>
                <button type="button" class="btn btn-outline-secondary"
                        wire:click="closeContactModal">{{ __('locale.Cancel') }}</button>
            </div>
        </form>
    </div>
</div>
