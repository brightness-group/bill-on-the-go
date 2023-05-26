{{-- Form User Company Modal --}}
@if ($action !== 'delete')
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                @if(!$selectedItem)
                    <h5 class="modal-title" id="userCompanyModalLabel">{{ __('locale.Create new User') }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                @else
                    <h5 class="modal-title" id="userCompanyModalLabel">{{ __('locale.Edit User') }}</h5>
                    <button type="button" class="close" style="background-color: #c9c9c9" wire:click="closeModal" aria-label="Close">
                        <span aria-hidden="true"><strong>&times;</strong></span>
                    </button>
                @endif
            </div>
            <div class="modal-body">
                <div>
                    <div class="mt-2">
                        <label class="form-label">{{ __('locale.Name') }}</label>
                        <input id="name" type="text" class="form-control flex-row" wire:model="name" > {{-- placeholder="{{ __('locale.Name') }}" --}}

                        @error('name') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-2">
                        <label class="form-label">{{ __('locale.Email') }}</label>
                        <input type="email" id="email" class="form-control flex-row" wire:model="email" > {{-- placeholder="{{ __('locale.Email') }}" --}}

                        @error('email') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-2">
                        <label class="form-label" for="selectCompanyUserRole">{{ __('locale.Role') }}</label>
                        <select class="form-control" id="selectCompanyUserRole" wire:change="selectedRoleItem($event.target.value)">
                            <option value="">{{ __('locale.Select') }}</option>

                            @foreach ($roles as $key => $role_)
                                <option value="{{ $key }}" {{ (!empty($roleSelected['id']) && $key == $roleSelected['id']) ? 'selected' : '' }}>{{ $role_ }}</option>
                            @endforeach
                        </select>

                        @error('roleSelected') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                    </div>
                    <br>
                    @if ($selectedItem)
                        {{-- password reset email send button --}}
                        <div class="mb-2">
                            <button type="button" class="btn btn-danger btn-sm" wire:click="sendResetLink" wire:loading.attr="disabled">
                                <span>{{ __('locale.Send Password Reset Link') }}</span>
                                <span style="margin-left: 5px;" wire:loading wire:target="sendResetLink">
                                    <div class="la-line-scale la-sm">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </span>
                            </button>
                        </div>

                        {{-- 2FA reset checkbox --}}
                        @if ($twoFactorAuthEnabled)
                            <div class="mt-2">
                                <input type="checkbox" wire:model="resetTwoFactorAuthEnabled" id="resetTwoFactorAuthEnabled">
                                <label for="resetTwoFactorAuthEnabled">{{ __('locale.Reset Two Factor Authentication') }}</label>
                            </div>
                        @endif

                        {{-- password reset checkbox --}}
                        <div class="mt-2">
                            <input type="checkbox" wire:model="changePassword" id="checkPasswordLabel">
                            <label for="checkPasswordLabel">{{ __('locale.Change Password') }}</label>
                        </div>

                        @if ($changePassword)
                            <div class="mt-2">
                                <label>{{ __('locale.Password') }}</label>
                                <input type="password" id="password" class="form-control flex-row" wire:model="password" placeholder="{{ __('locale.Password') }}">
                                @error('password') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                            </div>

                            @if (!empty($password))
                                <div class="mt-2">
                                    <label>{{ __('locale.Confirm Password') }}</label>
                                    <input type="password" class="form-control flex-row" wire:model="password_confirmation" placeholder="{{ __('locale.Password') }}">
                                    @error('password_confirmation') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                @if ($selectedItem)
                    <button class="btn btn-dark" wire:click="store" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="store">{{ __('locale.Save') }}</span>
                        <span style="width: 40px;margin: 0;padding-left: 10px;" wire:loading wire:target="store">
                            <div style="color: black;" class="la-line-scale la-sm">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </span>
                    </button>
                @else
                    <button class="btn btn-dark" wire:click="store" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="store">{{ __('locale.Create') }}</span>
                        <span style="width: 40px;margin: 0;padding-left: 10px;" wire:loading wire:target="store">
                            <div style="color: black;" class="la-line-scale la-sm">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </span>
                    </button>
                @endif

                <button class="btn btn-outline-secondary" wire:click="closeModal">{{ __('locale.Cancel') }}</button>
            </div>
        </div>
    </div>
@else
    {{-- Delete Company's User Modal --}}
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userCompanyDeleteModalLabel">{{ __('locale.Delete User') }}</h5>
                <button type="button" class="btn-close" wire:click="closeDeleteModal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3>{{ __('locale.Are you sure?') }}</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" wire:click="closeDeleteModal">{{ __('locale.Cancel') }}</button>
                <button type="button" class="btn btn-dark" wire:click="destroy">{{ __('locale.Yes') }}</button>
            </div>
        </div>
    </div>
@endif
