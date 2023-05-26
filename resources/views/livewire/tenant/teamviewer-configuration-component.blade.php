@section('title', __('locale.Teamviewer Configuration'))

<div>
    <div class="row">
        <div class="col-md-12 mb-1">
            <label class="form-label" for="anydesk_client_id">{{ __('locale.TV Client-ID') }}</label>
            <input type="text" id="anydesk_client_id" class="form-control"
                   wire:model="anydesk_client_id"/>
            @error('anydesk_client_id') <span class="error"
                                                 style="color: #ff0000;">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- tv account type = user --}}
    <div class="row">
        <div class="col-md-12 mb-1">
            <label class="form-label" for="anydesk_client_secret">{{ __('locale.TV Secret') }}</label>
            <input type="text" id="anydesk_client_secret" class="form-control"
                   wire:model="anydesk_client_secret">
            @error('anydesk_client_secret') <span class="error"
                                                     style="color: #ff0000;">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-right">
            <button class="btn btn-dark glow mb-sm-0 mr-sm-1 my-1" wire:loading.attr="disabled" wire:click="save">
                <span>{{ __('locale.Save') }}</span>
                <span style="margin-left: 5px;" wire:loading="" wire:target="save">
                            <div style="color: #F2F2F2;" class="la-line-scale la-sm">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </span>
            </button>
        </div>
    </div>
</div>
