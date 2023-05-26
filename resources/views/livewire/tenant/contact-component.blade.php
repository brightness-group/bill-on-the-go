<div>
    <h4>{{ __('locale.Contacts') }}</h4>

    <div class="row">
        <div class="col">
            <div class="text-end">
                <button wire:click="toggleContactDiv" class="btn btn-outline-dark" title="{{ __('locale.New Contact') }}">
                    <i class="bx bx-plus me-sm-2"></i>
                    {{ __('locale.New Contact') }}
                </button>
            </div>
        </div>
    </div>

    <br />

    <div id="collapseDivForContact" class="collapse collapse-div-for-contact p-3 custom-option" wire:ignore.self>
        <form wire:submit.prevent="save">
            @csrf

            <div class="row">
                <div class="col-sm-2">
                    <label for="salutation">{{ __('locale.Mr/Mrs') }}</label>
                    <select class="form-control" id="salutation" wire:model="salutation" style="-webkit-appearance: menulist;">
                        <option value="0">{{ __('locale.Mrs') }}</option>
                        <option value="1">{{ __('locale.Mr') }}</option>
                    </select>
                </div>

                <div class="col-sm-5">
                    <label for="contact_firstname">{{ __('locale.First Name') }}</label>
                    <input type="text" class="form-control" id="contact_firstname" wire:model="firstname">
                    @error('firstname') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                </div>

                <div class="col-sm-5">
                    <label for="contact_lastname">{{ __('locale.Last Name') }}</label>
                    <input type="text" class="form-control" id="contact_lastname" wire:model="lastname">
                    @error('lastname') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                </div>
            </div>

            <br />

            <div class="row">
                <div class="col-sm-6">
                    <label for="contact_department">{{ __('locale.Department') }}</label>
                    <input type="text" class="form-control" id="contact_department" wire:model="c_department">
                    @error('c_department') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                </div>

                <div class="col-sm-6">
                    <label for="contact_function">{{ __('locale.Function') }}</label>
                    <input type="text" class="form-control" id="contact_function" wire:model="c_function">
                    @error('c_function') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                </div>
            </div>

            <br />

            <div class="row">
                <div class="col-sm-4">
                    <label for="contact_b_number">{{ __('locale.Business') }}</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-phone"></i></span>
                        <input type="text" class="form-control" id="contact_b_number" wire:model="b_number">
                        @error('b_number') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <label for="contact_m_number">{{ __('locale.Mobile') }}</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-phone"></i></span>
                        <input type="text" class="form-control" id="contact_m_number" wire:model="m_number">
                        @error('m_number') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <label for="contact_h_number">{{ __('locale.Home') }}</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-phone"></i></span>
                        <input type="text" class="form-control" id="contact_h_number" wire:model="h_number">
                        @error('h_number') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <br />

            <div class="row">
                <div class="col-sm-6">
                    <label for="contact_s_email">{{ __('locale.Service Email') }}</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-mail-send"></i></span>
                        <input type="text" class="form-control" id="contact_s_email" wire:model="s_email">
                    </div>

                    @error('s_email') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                </div>

                <div class="col-sm-6">
                    <label for="contact_p_email">{{ __('locale.Private Email') }}</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-mail-send"></i></span>
                        <input type="text" class="form-control" id="contact_p_email" wire:model="p_email">
                    </div>

                    @error('p_email') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                </div>
            </div>

            <br />

            <div class="row">
                <div class="col-sm-12">
                    <label for="contact_devices">{{ __('locale.Select Device') }}</label>
                    <select {{ $customer ? '' : 'disabled' }} id="contact_devices" wire:model="selectedDevice" class="form-control contact_devices_multiple"
                            multiple="multiple" data-placeholder="{{ __('locale.Select') }}" style="width: 100%;">
                        @if($customer)
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}">{{ $device->alias }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <br />

            <div class="row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-dark" wire:target="save">{{ __('locale.Save') }}</button>
                    <button type="button" class="btn btn-secondary" wire:click="cancel">{{ __('locale.Cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="row pt-1">
        <table class="table table-sm table-striped table-hover table-contacts-component">
            <thead>
            <tr>
                <th></th>
                <th class="text-justify">{{ __('locale.Full Name') }}</th>
                <th>{{ __('locale.Function') }}</th>
                <th>{{ __('locale.Business') }}</th>
                <th>{{ __('locale.Mobile') }}</th>
{{--                <th>{{ __('locale.Home') }}</th>--}}
                <th>{{ __('locale.Service Email') }}</th>
{{--                <th>{{ __('locale.Private Email') }}</th>--}}
                <th colspan="2" class="text-center">{{ __('locale.Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @if(count($contacts))
                @foreach($contacts as $contact)
                    <tr>
                        <td wire:click="selectItem({{ $contact->id }},'update')" style="cursor: grabbing;">
                            @if($contact->salutation)
                                <i class="bx bx-male"></i>
                            @else
                                <i class="bx bx-female"></i>
                            @endif
                        </td>
                        <td class="text-justify" wire:click="selectItem({{ $contact->id }},'update')" style="cursor: grabbing;">{{ ucfirst($contact->firstname) }} {{ ucfirst($contact->lastname) }}</td>
                        <td wire:click="selectItem({{ $contact->id }},'update')" style="cursor: grabbing;">{{ $contact->c_function }}</td>
                        <td><a href="tel:{{ $contact->b_number }}">{{ $contact->b_number }}</a></td>
                        <td><a href="tel:{{ $contact->m_number }}">{{ $contact->m_number }}</a></td>
{{--                        <td><a href="tel:{{ $contact->h_number }}">{{ $contact->h_number }}</a></td>--}}
                        <td><a href="mailto:{{ $contact->s_email }}">{{ $contact->s_email }}</a></td>
{{--                        <td><a href="mailto:{{ $contact->p_email }}">{{ $contact->p_email }}</a></td>--}}
                        <td colspan="2" class="text-center">
                            <button class="btn btn-icon rounded-circle btn-light-secondary" wire:click="selectItem({{ $contact->id }},'update')" title="{{ __('locale.Edit') }}">
                                <i class="bx bx-edit"></i></button>
                            <button class="btn btn-icon rounded-circle btn-light-danger" wire:click="selectItem({{ $contact->id }},'delete')" title="{{ __('locale.Delete') }}">
                                <i class="bx bx-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="9" class="text-center">{{ __('locale.No results found') }}</td></tr>
            @endif
            </tbody>
        </table>
    </div>

    <!-- Delete Customer Modal -->
    <div class="modal fade" id="contactModalDelete" tabindex="-1" aria-labelledby="contactModalDeleteLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
                    <h5 class="modal-title" id="contactModalDeleteLabel">{{ __('locale.Delete Contact') }}</h5>
                    <button type="button" class="close" style="background-color: #c9c9c9" wire:click="closeContactModalDelete" aria-label="{{ __('locale.Close') }}">
                        <span aria-hidden="true"><strong>&times;</strong></span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>
                        {{ __('locale.Are you sure?') }}
                    </h3>

                    <h6>
                        {{ __('locale.Are you sure, you have the contact person') }} 
                        {{ $full_name }} 
                        {{ __('locale.Want to delete?') }}
                    </h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeContactModalDelete">{{ __('locale.Cancel') }}</button>
                    <button type="button" class="btn btn-dark" wire:click="destroy" wire:loading.attr="disabled">{{ __('locale.Yes') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
