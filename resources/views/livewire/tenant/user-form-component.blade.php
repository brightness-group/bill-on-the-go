<div>
    <div class="form-group">
        <label class="form-label">{{ __('locale.Name') }}</label>
        <input id="name" type="text" class="form-control flex-row" wire:model="name"> {{-- placeholder="{{ __('locale.Name') }}" --}}
        @error('name') <span class="error" style="color: #ff0000;">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">{{ __('locale.Email') }}</label>
        <input type="email" id="email" class="form-control flex-row" wire:model="email"> {{-- placeholder="{{ __('locale.Email') }}" --}}
        @error('email') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="selectTenantUserRole">{{ __('locale.Role')}}</label>
        <select class="form-control" id="selectTenantUserRole" wire:change="selectedRoleItem($event.target.value)">
            @foreach($roles as $key => $role_)
                @if($key == $roleSelected['id'])
                    <option value="{{ $key }}" selected>{{ $role_ }}</option>
                @else
                    <option value="{{ $key }}">{{ $role_ }}</option>
                @endif
            @endforeach
        </select>
        @error('roleSelected') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label for="selectAPIUser">{{ __('locale.API TV Users')}}</label>
        <select {{ $selectedTXTFileUser ? 'disabled' : '' }} class="form-control" id="selectAPIUser" wire:model="selectedAPIUsers">
            <option value="">{{ __('locale.Select') }}</option>
            @foreach($apiUsers as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        @error('selectedAPIUsers') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="selectFileSharedUser">{{ __('locale.Txt File Users')}}</label>
        <select {{ $selectedAPIUsers ? 'disabled' : '' }} class="form-control" id="selectFileSharedUser" wire:model="selectedTXTFileUser">
            <option value="">{{ __('locale.Select') }}</option>
            @foreach($txtFileUsers as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        @error('selectedTXTFileUser') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
    </div>
{{--
    ## commented code as no required for now at least
    @if($modelId)
        <div class="row">
            <div class="col-auto d-flex display-block">
                <div class="form-check form-check-primary mt-3">
                    <input class="form-check-input" type="checkbox" wire:change="manageUserApiToken($event.target.value)"
                           {{ $allow_api ? 'checked' : '' }} id="api_access_checkbox">
                    <label class="form-label" for="api_access_checkbox">{{ __('locale.Allow API access') }}</label>
                </div>
            </div>
        </div>
    @endif
    <div class="form-group my-1">
        <div class="justify-content-center align-items-center">
            @if($modelId)
                <button type="button" class="btn btn-sm btn-danger" wire:click="sendResetLink" wire:loading.attr="disabled">
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
            @endif
        </div>
    </div>
--}}
    <div class="form-group justify-content-start mt-3">
        <div class="justify-content-center align-items-center">
            @if($modelId)
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
            <button class="btn btn-outline-dark" wire:click.prevent="closeFormUserModal" wire:loading.attr="disabled"> {{ __('locale.Cancel') }}</button>
        </div>
    </div>
</div>






