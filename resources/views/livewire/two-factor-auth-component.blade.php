<div>
    <h4>{{ __('locale.Two Factor Authentication') }}</h4>
    <p>{{ __('locale.Two-factor authentication adds an additional layer of security to your account by requiring more than just a password to log in.') }}</p>
    <hr>
    @if($password_is_confirm)
        <div class="form-group">
            @if($two_factor_secret)
                <p><strong>{{ __('locale.Status: Enabled') }}</strong></p>
                <div class="mb-3">
                    <button class="btn btn-danger" wire:click="disableTwoFactorAuthentication">{{ __('locale.Disable') }}</button>
                </div>
                <div class="mb-2">
                    {!! auth()->user()->twoFactorQrCodeSvg() !!}
                </div>
                <div>
                    <h3>{{ __('locale.Recovery Codes:') }}</h3>
                    @foreach(json_decode(decrypt(auth()->user()->two_factor_recovery_codes, true)) as $code)
                        {{ trim($code) }} <br>
                    @endforeach
                </div>
            @else
                <p><strong>{{ __('locale.Status: Disabled') }}</strong></p>
                <div class="gl-mb-3">
                    <button class="btn btn-success" wire:click="enableTwoFactorAuthentication">{{ __('locale.Enable') }}</button>
                </div>
            @endif
        </div>
    @else
        @if(!$two_factor_secret)
            <div class="form-group">
                <p><strong>{{ __('locale.2FA Status: Disabled') }}</strong></p>
                <p>{{ __('locale.First be sure confirming your password. After, you are able to Enable it') }}</p>
                <button class="btn btn-dark" wire:click="settingUP">{{ __('locale.Confirm') }}</button>
            </div>
        @else
            <div class="form-group">
                <p><strong>{{ __('locale.2FA Status: Enabled') }}</strong></p>
                <p>{{ __('locale.First be sure confirming your password. After, you are able to Disable it') }}</p>
                <button class="btn btn-dark" wire:click="settingUP">{{ __('locale.Confirm') }}</button>
            </div>
        @endif
    @endif
</div>
