<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form>
            <div class="modal-header modal-header-add-user box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
                <h5 class="modal-title" id="addNewUserModalLabel">{{ __('locale.Create new User') }}</h5>
                <button type="button" wire:click="closeUserModal" class="btn-close"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    @csrf
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="user_name">{{ __('locale.Name') }}</label>
                            <input type="text" class="form-control" id="user_name" wire:model="user_name">
                            @error('user_name') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="email">{{ __('locale.Email') }}</label>
                            <input type="email" id="email" class="form-control flex-row" wire:model="email">
                            @error('email') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="selectTenantUserRole">{{ __('locale.Role')}}</label>
                            <select class="form-control" id="selectTenantUserRole"
                                    wire:change="selectedRoleItem($event.target.value)">
                                @foreach($roles as $key => $role_)
                                    @if($key == $roleSelected['id'])
                                        <option value="{{ $key }}" selected>{{ $role_ }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $role_ }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('roleSelected') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="selectAPIUser">{{ __('locale.API Users')}}</label>
                            <select {{ $selectedTXTFileUser ? 'disabled' : '' }} class="form-control"
                                    id="selectAPIUser" wire:model="selectedAPIUsers">
                                <option value="">{{ __('locale.Select') }}</option>
                                @foreach($apiUsers as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('selectedAPIUsers') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <label class="form-label" for="selectFileSharedUser">{{ __('locale.Txt File Users')}}</label>
                            <select {{ $selectedAPIUsers ? 'disabled' : '' }} class="form-control"
                                    id="selectFileSharedUser" wire:model="selectedTXTFileUser">
                                <option value="">{{ __('locale.Select') }}</option>
                                @foreach($txtFileUsers as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('selectedTXTFileUser') <span class="text-danger fs-tiny fw-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div class="col">
                </div>
                <div class="col ml-auto d-flex justify-content-end">
                    <button wire:loading.attr="disabled" wire:click="save" style="margin-right: 3px;"
                            {{ !auth()->user()->hasRole('Admin') ? 'disabled' : '' }} type="button"
                            class="btn btn-dark">
                        <span wire:loading.remove wire:target="save">{{ __('locale.Save') }}</span>
                        <span style="width: 40px;margin: 0;padding-left: 10px;" wire:loading
                              wire:target="save">
                            <div style="color: white;" class="la-line-scale la-sm">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </span>
                    </button>
                    <button type="button" class="btn btn-outline-secondary"
                            wire:click="closeUserModal">{{ __('locale.Cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

