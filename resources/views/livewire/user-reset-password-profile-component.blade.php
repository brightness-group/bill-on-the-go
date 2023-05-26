<div>
    <div class="row">
        <div class="col-sm-10 mb-2" >
            <label class="form-label">{{ __('locale.Old Password') }}</label>
            <input type="password" class="form-control" id="current_password" wire:model="current_password"> {{-- placeholder="{{ __('locale.Old Password') }}" autocomplete="off" --}}
            @if($errors->has('current_password'))
                <p style="color: #ff0000">{{ $errors->first('current_password') }}</p>
            @endif
        </div>
{{--        <div class="col-sm-1 mb-2 justify-content-center" wire:poll.1000ms="timeElapsed">--}}
{{--            <input type="hidden" class="form-control" wire:model="time_elapsed" style="text-align: center" readonly>--}}
{{--        </div>--}}
        <div class="col-sm-10 mb-2">
            <label class="form-label">{{ __('locale.New Password') }}</label>
            <input type="password" class="form-control" wire:model="password"> {{-- placeholder="{{ __('locale.New Password') }}" autocomplete="off" --}}
            @if($errors->has('password'))
                <p style="color: #ff0000">{{ $errors->first('password') }}</p>
            @endif
        </div>
        <div class="col-sm-10 mb-2">
            <label class="form-label">{{ __('locale.Confirm new Password') }}</label>
            <input type="password" class="form-control" wire:model="password_confirmation"> {{-- placeholder="{{ __('locale.Confirm new Password') }}" autocomplete="off" --}}
            @if($errors->has('password_confirmation'))
                <p style="color: #ff0000">{{ $errors->first('password_confirmation') }}</p>
            @endif
        </div>
    </div>
    <div class="mt-2">
        <button class="btn btn-dark glow me-sm-1 mb-1" wire:click="save">{{ __('locale.Save') }}</button>
    </div>
</div>
