<div class="row">
    {{-- square logo --}}
    <div class="col-12">
        <label class="form-label" for="input-logo" class="">{{ __('locale.Square Logo') }}</label>
        <br />
        <div class="align-items-start align-items-sm-center gap-4 media">
            @if($logo)
                <div class="company-square-logo">
                    <img class="rounded mr-75" src="{{ $logo->temporaryUrl() }}" alt="Logo"
                         height="60" width="60" style="">
                </div>
            @elseif($prevLogo)
                <div class="company-square-logo">
                    <img class="rounded mr-75" src="{{ url(\App\Helpers\CoreHelpers::getFileUrl($prevLogo, 'square')) }}" alt="Logo" height="60"
                         width="60">
                </div>
            @else
                <div class="company-square-logo">
                    {{-- <img class="rounded mr-75" src="{{ mix('assets/images/backgrounds/user_icon.png') }}" alt="Logo" height="60" width="60"> --}}
                    <i class="bx bx-buildings bx-lg"></i>
                </div>
            @endif
            <div class="button-wrapper">
                <div class="media-body mt-25">
                    <div
                        class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                        <label for="input-logo" class="btn btn-sm btn-dark me-2 mt-3"
                               tabindex="0">
                            <span>{{ __('locale.Upload new image Square') }}</span>
                            <div class="text-nowrap" style="margin-left: 5px;" wire:loading
                                 wire:target="logo">
                                <div style="color: #a779e9;" class="la-line-scale la-sm">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>

                            <input type="file" id="input-logo" hidden
                                   wire:model.debounce.1100ms="logo" wire:loading="disabled">
                        </label>

                        <button id="logo-reset" type="button"
                                class="btn btn-sm btn-outline-dark account-image-reset mt-3"
                                wire:click="onResetButton">
                            <i class="bx bx-reset d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('locale.Reset') }}</span>
                        </button>
                    </div>
                    <p class="text-muted ml-1 mt-50">
                        <small>{{ __('locale.Allowed JPG, GIF or PNG. Max size of 1MB') }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- rectangle logo --}}
    <div class="col-12 mt-5">
        <label class="form-label" for="input-rectangle-logo" class="">{{ __('locale.Sidebar Rectangle Logo') }}</label>
        <br />
        <div class="align-items-start align-items-sm-center gap-4 media">
            @if($rectangleLogo)
                <div class="company-rectangle-logo">
                    <img class="rounded mr-75" src="{{ $rectangleLogo->temporaryUrl() }}" alt="Rectangle Logo"
                         height="80" width="280" style="">
                </div>
            @elseif($prevRectangleLogo)
                <div class="company-rectangle-logo">
                    <img class="rounded mr-75" src="{{ url(\App\Helpers\CoreHelpers::getFileUrl($prevRectangleLogo, 'rectangle')) }}" alt="Rectangle Logo"
                         height="60" width="280">
                </div>
            @else
                <div class="company-rectangle-logo">
                    {{-- <img class="rounded mr-75" src="{{ mix('assets/images/backgrounds/user_icon.png') }}" alt="Logo" height="60" width="60"> --}}
                    <i class="bx bx-buildings bx-lg"></i>
                </div>
            @endif

            <div class="button-wrapper">
                <div class="media-body mt-25">
                    <div
                        class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                        <label for="input-rectangle-logo" class="btn btn-sm btn-dark me-2 mt-3"
                               tabindex="0">
                            <span>{{ __('locale.Upload new image Rectangle') }}</span>
                            <div class="text-nowrap" style="margin-left: 5px;" wire:loading
                                 wire:target="rectangleLogo">
                                <div style="color: #a779e9;" class="la-line-scale la-sm">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>

                            <input type="file" id="input-rectangle-logo" hidden
                                   wire:model.debounce.1100ms="rectangleLogo" wire:loading="disabled">
                        </label>

                        <button id="rectangle_logo-reset" type="button"
                                class="btn btn-sm btn-outline-dark account-image-reset mt-3"
                                wire:click="onResetButtonForRectangleLogo">
                            <i class="bx bx-reset d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('locale.Reset') }}</span>
                        </button>
                    </div>
                    <p class="text-muted ml-1 mt-50">
                        <small>{{ __('locale.Allowed JPG, GIF or PNG. Max size of 1MB') }}</small>
                    </p>
                </div>
            </div>
            <p>
                @error('rectangle_logo') <span class="error"
                                               style="color: #ff0000">{{ $message }}</span> @enderror
            </p>
        </div>
    </div>

    {{-- save button --}}
    <div class="col-12">
        <button class="btn btn-dark glow mb-sm-0 mr-sm-1 my-1" wire:loading.attr="disabled"
                wire:click="save">
            <span>{{ __('locale.Save') }}</span>
            <span style="margin-left: 5px;" wire:loading wire:target="save">
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
