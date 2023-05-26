<div>
    <div class="d-flex align-items-start align-items-sm-center gap-4 media">
        @if(!empty($profile_photo))
            <img src="{{ $profile_photo->temporaryUrl() }}"
                class="rounded mr-75" height="64" width="64">
        @elseif(!empty($prevProfilePhoto))
            <img src="{{ url($prevProfilePhoto) }}"
                class="rounded mr-75" height="64" width="64">
        @else
            <img src="{{ mix('assets/images/backgrounds/user_icon.png') }}"
                class="rounded mr-75" alt="profile image" height="64" width="64">
        @endif

        <div class="button-wrapper">
            <div class="media-body mt-25">
                <div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                    <label for="select-files" class="btn btn-sm btn-dark me-2 mt-3" tabindex="0">
                        <span>{{ __('locale.Upload new photo') }}</span>
                        <div class="text-nowrap ml-1" wire:loading wire:target="profile_photo">
                            <div style="color: #a779e9;" class="la-line-scale la-sm">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>

                        <input id="select-files" type="file" wire:model.debounce.1100ms="profile_photo" hidden>
                    </label>

                    <button class="btn btn-sm btn-outline-dark account-image-reset mt-3" wire:click="onResetButton">
                        <i class="bx bx-reset d-block d-sm-none"></i>
                        {{ __('locale.Reset') }}
                    </button>
                </div>
                <p class="text-muted ml-1 mt-50"><small>{{ __('locale.Allowed JPG, GIF or PNG. Max size of 1MB') }}</small></p>
            </div>
        </div>
        <p>
            @error('profile_photo') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
        </p>
    </div>
    <div class="mb-2">
        @if(session()->has('message'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ session('message') }}
            </div>
        @endif
    </div>
    <div class="mb-2">
        <label class="form-label">{{ __('locale.Name') }}</label>
        <input type="text" wire:model="name" class="form-control" value="{{ $name }}">
        @if($errors->has('name'))
            <p style="color: #ff0000">{{ $errors->first('name') }}</p>
        @endif
    </div>
    <div class="mb-2">
        <label class="form-label">{{ __('locale.Email') }}</label>
        <input type="email" wire:model="email" class="form-control" value="{{ $email }}">
        @if($errors->has('email'))
            <p style="color: #ff0000">{{ $errors->first('email') }}</p>
        @endif
    </div>
    <div class="mt-2">
        <button class="btn btn-dark" wire:click="save">{{ __('locale.Save') }}</button>
    </div>

    <!-- Delete Photo Modal -->
    <div class="modal fade" id="modalFormDeletePhotoPath" tabindex="-1" aria-labelledby="deletePhotoPathModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePhotoPathModalLabel">{{ __('locale.Totally delete photo profile') }}</h5>
                    <button type="button" class="close" wire:click="closeDeletePhotoModal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>{{ __('locale.Are you sure?') }}</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="closeDeletePhotoModal">{{ __('locale.Cancel') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="deletePhotoAndActions">{{ __('locale.Yes') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('custom_scripts')
    <script>

        window.addEventListener('openDeletePhotoPathModal', event => {
            $("#modalFormDeletePhotoPath").modal('show');
        })

        window.addEventListener('closeDeletePhotoPathModal', event => {
            $("#modalFormDeletePhotoPath").modal('hide');
        })
        $("#modalFormDeletePhotoPath").on('hidden.bs.modal', function(){
            livewire.emit('forcedCloseModal');
        });

        $(document).ready(function(){

            window.livewire.on('alert_remove',()=>{
                setTimeout(function(){ $(".alert-success").fadeOut('fast');
                }, 5000); // 5 secs
            });
        });

    </script>
@endsection
