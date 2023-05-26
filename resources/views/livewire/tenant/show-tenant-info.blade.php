@section('title',__('locale.My Company'))

@section('custom_css')
    <link rel="stylesheet" href="{{ mix('frontend/css/loading_states_awesome.css') }}">
    <link rel="stylesheet" href="{{ mix('frontend/css/users-component_css.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/css/company_component_css.css') }}">
@endsection

@section('vendor-style')
    <link rel="stylesheet" href="{{ mix('assets/vendor/libs/bs-stepper/bs-stepper.css') }}"/>
@endsection

<section>
    <div class="row">
        <div>
            <div id="wizard-create-deal" class="bs-stepper vertical mt-2 linear"
                 style="min-height: calc(100vh - 120px) !important;">
                <div class="bs-stepper-header" style="min-width: 20rem;">
                    <div class="step {{$contentShow == 'basic' ? 'active' : ''}}">
                        <button type="button" class="step-trigger" aria-selected="true" >
                            <span class="bs-stepper-circle" wire:click="$set('contentShow','basic')">
                                <i class="bx bx-pin"></i>
                            </span>

                            <span class="bs-stepper-label">
                                <a class="d-flex align-items-center" data-toggle="pill" href="javascript:void(0)"
                                   aria-expanded="false" wire:click="$set('contentShow','basic')">
                                    {{ __('locale.Basic') }}
                                </a>
                            </span>
                        </button>
                    </div>

                    <div class="step {{$contentShow == 'billing' ? 'active' : ''}}">
                        <button type="button" class="step-trigger" aria-selected="true">
                            <span class="bs-stepper-circle" wire:click="$set('contentShow','billing')">
                                <i class="bx bx-analyse"></i>
                            </span>

                            <span class="bs-stepper-label">
                                <a class="d-flex align-items-center" data-toggle="pill" href="javascript:void(0)"
                                   aria-expanded="false" wire:click="$set('contentShow','billing')">
                                    {{ __('locale.Billing') }}
                                </a>
                            </span>
                        </button>
                    </div>

                    <div class="step {{$contentShow == 'user-management' ? 'active' : ''}}">
                        <button type="button" class="step-trigger" aria-selected="true">
                            <span class="bs-stepper-circle" wire:click="$set('contentShow','user-management')">
                                <i class="bx bx-user"></i>
                            </span>

                            <span class="bs-stepper-label">
                                <a class="d-flex align-items-center" data-toggle="pill" href="javascript:void(0)"
                                   aria-expanded="false" wire:click="$set('contentShow','user-management')">
                                    {{ __('locale.User Management') }}
                                </a>
                            </span>
                        </button>
                    </div>
                </div>

                {{-- tab contents --}}
                <div class="bs-stepper-content">
                    @if($contentShow == 'basic')
                        <div id="#tab-basic" role="tabpanel" aria-labelledby="account-pill-connect" aria-expanded="true">
                            <div class="row">
                                <div class="col">
                                    <label class="form-label" for="input-logo" class="">{{ __('locale.Logo') }}</label>
                                    <br/>
                                    <div class="d-flex align-items-start align-items-sm-center gap-4 media">
                                        @if($logo)
                                            <img class="rounded mr-75" src="{{ $logo->temporaryUrl() }}" alt="Logo"
                                                height="60" width="60" style="">
                                        @elseif($prevLogo)
                                            <img class="rounded mr-75" src="{{ url($prevLogo) }}" alt="Logo" height="60"
                                                width="60">
                                        @else
                                            {{-- <img class="rounded mr-75" src="{{ mix('assets/images/backgrounds/user_icon.png') }}" alt="Logo" height="60" width="60"> --}}
                                            <i class="bx bx-buildings bx-lg"></i>
                                        @endif

                                        <div class="button-wrapper">
                                            <div class="media-body mt-25">
                                                <div
                                                    class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                                                    <label class="form-label" for="input-logo" class="btn btn-sm btn-dark me-2 mt-3"
                                                        tabindex="0">
                                                        <span>{{ __('locale.Upload new photo') }}</span>
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
                                        <p>
                                            @error('logo') <span class="error"
                                                                style="color: #ff0000">{{ $message }}</span> @enderror
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <br/>

                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label" for="name">{{ __('locale.Company Name') }}</label>
                                    <input type="text" id="name" class="form-control"
                                        wire:model="name"> {{-- placeholder="{{ __('locale.Name') }}" --}}
                                    @error('name') <span class="error"
                                                        style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label" for="address">{{ __('locale.Address') }}</label>
                                    <input type="text" id="address" class="form-control" wire:model="address">
                                    @error('address') <span class="error"
                                                            style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row pt-1">
                                <div class="col-6">
                                    <label class="form-label" for="zip">{{ __('locale.ZIP') }}</label>
                                    <input type="text" id="zip" class="form-control" wire:model="zip">
                                    @error('zip') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="city">{{ __('locale.City') }}</label>
                                    <input type="text" id="city" class="form-control" wire:model="city">
                                    @error('city') <span class="error"
                                                        style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row pt-1">
                                <div class="col-6">
                                    <label class="form-label" for="country">{{ __('locale.Country') }}</label>
                                    <input type="text" id="country" class="form-control" wire:model="country">
                                    @error('country') <span class="error"
                                                            style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="email">{{ __('locale.Company Email') }}</label>
                                    <input type="email" id="email" class="form-control" wire:model="email">
                                    @error('email') <span class="error"
                                                        style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row pt-1">
                                <div class="col-6">
                                    <label class="form-label" for="phone">{{ __('locale.Phone number') }}</label>
                                    <input type="text" id="phone" class="form-control" wire:model="phone">
                                    @error('phone') <span class="error"
                                                        style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-6">
                                    <fieldset>
                                        <label class="form-label" for="website">{{ __('locale.Website') }}</label>
                                        <div class="input-group d-flex">
                                            {{-- <div class="input-group-prepend"> --}}
                                            {{-- <span class="input-group-text" id="url">https://</span> --}}
                                            {{-- </div> --}}
                                            <input type="text" wire:model="website" class="form-control" id="website"
                                                placeholder="" aria-describedby="basic-addon1">
                                        </div>
                                    </fieldset>

                                    {{-- <label class="form-label" for="website">{{ __('locale.Website') }}</label>--}}
                                    {{-- <input type="url" id="website" class="form-control" wire:model="website">--}}
                                    @error('website') <span class="error"
                                                            style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            {{-- <div class="row pt-1">
                                <div class="col-6">
                                    <label class="form-label" for="notes">{{ __('locale.Notes') }}</label>
                                    <textarea name="" id="notes" cols="40" rows="3" class="form-control" maxlength="500" wire:model="notes"></textarea>
                                    @error('notes') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                            </div> --}}
                        </div>
                    @elseif($contentShow == 'billing')
                        <div id="#tab-billing" role="tabpanel" aria-labelledby="account-pill-connect" aria-expanded="false">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label" for="billing_address">{{ __('locale.Invoice Address') }}</label>
                                    <input type="text" id="billing_address" class="form-control"
                                        wire:model="billing_address">
                                    @error('billing_address') <span class="error"
                                                                    style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="payment">{{ __('locale.Payment Method') }}</label>
                                    <input type="text" id="payment" class="form-control" wire:model="payment">
                                    @error('payment') <span class="error"
                                                            style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label" for="tax_number">{{ __('locale.VAT ID / Tax Number') }}</label>
                                    <input type="text" id="tax_number" class="form-control bic" wire:model="tax_number">
                                    @error('tax_number') <span class="error"
                                                            style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-6" wire:ignore.self>
                                    <label class="form-label" for="iban">IBAN</label>
                                    <input type="text" id="iban" class="form-control iban" wire:model="iban"> {{----}}
                                    @error('iban') <span class="error"
                                                        style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label" for="bic">BIC</label>
                                    <input type="text" id="bic" class="form-control bic" wire:model="bic">
                                    @error('bic') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <hr style="font-weight: bolder; width: 100%;left: 0;margin: 2rem 0;">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label" for="contact">{{ __('locale.Contact') }}</label>
                                    <input type="text" id="contact" class="form-control" wire:model="contact">
                                    @error('contact') <span class="error"
                                                            style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="contact_email">{{ __('locale.Contact Email') }}</label>
                                    <input type="text" id="contact_email" class="form-control" wire:model="contact_email">
                                    @error('contact_email') <span class="error"
                                                                style="color: #ff0000">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @elseif($contentShow == 'user-management')
                        <div id="#tab-user-management" role="tabpanel" aria-labelledby="account-pill-connect">
                            @livewire('tenant.users-component', ['search' => $search])
                        </div>
                    @endif

                    <!-- Delete Photo Modal -->
                    <div class="modal fade" id="modalFormDeleteLogo" tabindex="-1"
                         aria-labelledby="deleteLogoModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"
                                        id="deleteLogoModalLabel">{{ __('locale.Totally delete photo profile') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                            wire:click="closeDeletePhotoModal">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h3>{{ __('locale.Are you sure?') }}</h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                            wire:click="closeDeletePhotoModal">{{ __('locale.Cancel') }}</button>
                                    <button type="button" class="btn btn-dark"
                                            wire:click="deletePhoto">{{ __('locale.Yes') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($contentShow != 'user-management')
                        <div class="bs-stepper-footer">
                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@section('page-script')
    <script defer src="{{ mix('assets/vendor/libs/jquery-mask-plugin/dist/jquery.mask.min.js') }}"></script>

    <script defer type="text/javascript">
        $(document).ready(function () {
            window.addEventListener('searchUpdate', event => {
                const url = new URL(window.location);

                url.searchParams.set('search', event.detail.search);

                window.history.pushState(null, '', url.toString());
            });

            window.initInputMasks = () => {
                $('.iban').mask('SS00-0000-0000-0000-0000-00');
                $('.bic').mask('0000');
            };
            initInputMasks();
            window.livewire.on('inputMasksHydrate', () => {
                initInputMasks();
            });

            window.addEventListener('loadIbanBicInputs', event => {
                $('.iban').val = event.detail.iban;
                $('.bic').val = event.detail.bic;
            });

            window.addEventListener('focusErrorInput', event => {
                var $field = '#' + event.detail.field;
                $($field).focus()
            });
            toastr.options = {
                positionClass: 'toast-top-center',
                showDuration: 1000,
                timeOut: 3000,
                hideDuration: 2000,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut',
                preventDuplicates: false,
            }

            window.initAPIKeysElements = () => {
                var copyTextareaBtn = document.querySelector('#clipboard_button');
                if (copyTextareaBtn) {
                    copyTextareaBtn.addEventListener('click', function (event) {
                        var copyTextarea = document.querySelector('#plainTextToken');
                        copyTextarea.focus();
                        copyTextarea.select();

                        try {
                            var successful = document.execCommand('copy');
                            var msg = successful ? 'successful' : 'unsuccessful';
                            console.log('Copying text command was ' + msg);
                        } catch (err) {
                            console.log('Oops, unable to copy');
                        }
                    });
                }
            };
            window.livewire.on('initAPIKeysElements', () => {
                initAPIKeysElements();
            });

            window.addEventListener('showToastrSuccess', event => {
                toastr.success('', event.detail.message).css("width", "fit-content")
            })
            window.addEventListener('showToastrDelete', event => {
                toastr.warning('', event.detail.message).css("width", "fit-content")
            })
            window.addEventListener('showToastrError', event => {
                toastr.error('', event.detail.message).css("width", "fit-content")
            })
        });
        window.addEventListener('openDeleteLogoModal', event => {
            $("#modalFormDeleteLogo").modal('show');
        })

        window.addEventListener('closeDeleteLogoModal', event => {
            $("#modalFormDeleteLogo").modal('hide');
        })

        window.addEventListener('openUserDeleteModal', event => {
            $("#userDeleteModal-" + event.detail.userId).modal('show');
        })

        window.addEventListener('closeUserDeleteModal', event => {
            $("#userDeleteModal-" + event.detail.userId).modal('hide');
        })

        window.addEventListener('openFormUserModal', event => {
            $("#formUserModal").modal('show');
        })

        window.addEventListener('closeFormUserModal', event => {
            $("#formUserModal").modal('hide');
        })
    </script>
@endsection
