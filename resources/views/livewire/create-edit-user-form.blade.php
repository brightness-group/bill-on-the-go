<div>
    <div class="modal-body">
        <div class="mb-2">
            <label class="form-label">{{ __('locale.Name')}}</label>
            <input id="name" type="text" class="form-control flex-row" wire:model="name"> {{--placeholder="{{ __('locale.Name')}}"--}}
            @error('name') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
        </div>
        <div class="mb-2">
            <label class="form-label">{{ __('locale.Email')}}</label>
            <input type="email" id="email" class="form-control flex-row" wire:model="email"> {{-- placeholder="{{ __('locale.Email')}}" --}}
            @error('email') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="selectSystemUserRole">{{ __('locale.Role')}}</label>
            <select class="form-control" id="selectSystemUserRole" wire:change="selectedRoleItem($event.target.value)">
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
        <div class="mb-2">
            @if($modelId)
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
            @endif
        </div>
    </div>
    <div class="modal-footer">
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
        <button class="btn btn-outline-secondary" wire:click="closeFormModal">{{ __('locale.Cancel') }}</button>
    </div>
</div>
