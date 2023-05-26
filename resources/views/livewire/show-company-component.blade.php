<div>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ !empty($company) ? __('locale.Edit Company') : __('locale.New Company') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="$emit('refreshParent')"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-end">
                        @if (!$isCreate)
                            @if (auth('web')->user()->hasRole('Admin'))
                                @if($company->status)
                                    <button target="_blank" class="btn btn-sm btn-icon text-secondary" disabled="disabled" data-toggle="modal" data-placement="top" title="">
                                        <i class="bx bx-link-alt"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon text-secondary" disabled="disabled" data-toggle="modal" data-placement="top" title="">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                    <button wire:click="$emit('statusSwitcher', {{ $company->id }}, 'lock')" class="btn btn-sm btn-icon text-secondary" data-toggle="modal" data-placement="top" title="{{ __('locale.Unlock') }}">
                                        <i class="bx bx-lock"></i>
                                    </button>
                                @else
                                    <a href="{{ $company->generateFullURL() }}" target="_blank" class="btn btn-sm btn-icon text-secondary" data-placement="top" title="{{ __('locale.Link') }}">
                                        <i class="bx bx-link-alt"></i>
                                    </a>
                                    <button wire:click="$emit('selectItem', {{ $company->id }}, 'delete')" class="btn btn-sm btn-icon text-secondary" data-placement="top" title="{{ __('locale.Delete') }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                    <button wire:click="$emit('statusSwitcher', {{ $company->id }}, 'lock')" class="btn btn-sm btn-icon text-secondary" data-placement="top" title="{{ __('locale.Lock') }}">
                                        <i class="bx bx-lock-open"></i>
                                    </button>
                                @endif
                            @endif
                        @endif
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-2" role="tablist">
                                <li class="nav-item">
                                    <a id="basic-tab" class="nav-link d-flex align-items-center {{ $tab == 'basic' ? 'active' : '' }}" role="tab" aria-selected="true"
                                    aria-controls="basic" wire:click="$set('tab','basic')" href="#"><i class="bx bx-pin"></i><span class="d-none d-sm-block">{{ __('locale.Basic') }}</span></a>
                                </li>
                                <li class="nav-item">
                                    <a id="billing-tab" class="nav-link d-flex align-items-center {{ $tab == 'billing' ? 'active' : '' }}" role="tab" aria-selected="true"
                                    aria-controls="billing" wire:click="$set('tab','billing')" href="#"><i class="bx bx-analyse"></i><span class="d-none d-sm-block">{{ __('locale.Billing') }}</span></a>
                                </li>
                                @if (!$isCreate)
                                    <li class="nav-item">
                                        <a id="users-tab" class="nav-link d-flex align-items-center {{ $tab == 'users' ? 'active' : '' }}" role="tab" aria-selected="true"
                                        aria-controls="users" wire:click="$set('tab', 'users')" href="#"><i class="bx bx-group"></i><span class="d-none d-sm-block">{{ __('locale.Users') }}</span></a>
                                    </li>
                                @endif
                            </ul>
                            <div class="tab-content">
                                @if($tab == 'basic')
                                    <div id="basic" aria-labelledby="basic-tab">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="form-label" for="name">{{ __('locale.Company Name') }}</label>
                                                <input type="text" id="name" class="form-control" wire:model="name"> {{-- placeholder="{{ __('locale.Name') }}" --}}
                                                @error('name') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label" for="subdomain">{{ __('locale.Subdomain') }}</label>
                                                <input type="text" id="subdomain" class="form-control" wire:model="subdomain"> {{-- placeholder="{{ __('locale.Subdomain') }}" --}}
                                                @error('subdomain') <span class="error" style="color: #ff0000;">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                
                                        <br />
                
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="input-logo" class="mb-2 form-label">{{ __('locale.Logo') }}</label>
                                                <div class="d-flex align-items-start align-items-sm-center gap-4 media">
                                                    @if (!is_null($company))
                                                        @if($logo)
                                                            <img class="rounded mr-75" src="{{ $logo->temporaryUrl() }}" alt="Logo" height="60" width="60">
                                                        @elseif($prevLogo)
                                                            <img class="rounded mr-75" src="{{ url($prevLogo) }}" alt="Logo" height="60" width="60">
                                                        @else
                                                            <i class="bx bx-buildings bx-lg"></i>
                                                        @endif
                                                    @else
                                                        @if($logo)
                                                            <img class="rounded mr-75" src="{{ $logo->temporaryUrl() }}" alt="Logo" height="60" width="60">
                                                        @else
                                                            <i class="bx bx-buildings bx-lg"></i>
                                                        @endif
                                                    @endif
                
                                                    <div class="button-wrapper">
                                                        <div class="media-body mt-25">
                                                            <div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start" id="hoverable">
                                                                <label class="btn btn-sm btn-dark me-2 mt-3 form-label" for="input-logo">
                                                                    <span>{{ __('locale.Upload new Logo') }}</span>
                                                                    <div class="text-nowrap" style="margin-left: 5px;" wire:loading wire:target="logo">
                                                                        <div style="color: #a779e9;" class="la-line-scale la-sm">
                                                                            <div></div>
                                                                            <div></div>
                                                                            <div></div>
                                                                            <div></div>
                                                                            <div></div>
                                                                        </div>
                                                                    </div>
                                                                    <input type="file" id="input-logo" hidden wire:model.debounce.1100ms="logo" wire:loading="disabled" hidden>
                                                                </label>
                                                                <button id="logo-reset" class="btn btn-sm btn-outline-dark account-image-reset mt-3 text-uppercase" wire:click="onResetButton">
                                                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                                                    {{ __('locale.Reset') }}
                                                                </button>
                                                            </div>
                                                            <p class="text-muted ml-1 mt-50"><small>{{ __('locale.Allowed JPG, GIF or PNG. Max size of 1MB') }}</small></p>
                                                        </div>
                                                        <p>
                                                            @error('logo') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <label class="form-label" for="customer_type_id">{{ __('locale.Customer Type') }}</label>
                                                <select class="form-control" wire:model="customer_type_id" id="customer_type_id">
                                                    <option value="">{{ __('locale.Select') }}</option>
                                                    @if (!empty($customerTypes) && !$customerTypes->isEmpty())
                                                        @foreach ($customerTypes as $customerType)
                                                            <option value="{{ $customerType->id }}">
                                                                @switch ($customerType->type)
                                                                    @case("0")
                                                                        {{ __('locale.All') }}
                                                                        @break
                                                                    @case("1")
                                                                        {{ __('locale.Church') }}
                                                                        @break
                                                                    @case("2")
                                                                        {{ __('locale.SME') }}
                                                                        @break
                                                                    @case("3")
                                                                        {{ __('locale.School') }}
                                                                        @break
                                                                    @case("4")
                                                                        {{ __('locale.Authorities') }}
                                                                        @break
                                                                    @case("5")
                                                                        {{ __('locale.Association') }}
                                                                        @break
                                                                    @case("6")
                                                                        {{ __('locale.Health Care') }}
                                                                        @break
                                                                    @case("7")
                                                                        {{ __('locale.Medical Professions') }}
                                                                        @break
                                                                @endswitch
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                                @error('customer_type_id') <span class="error" style="color: #ff0000;">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                @elseif($tab == 'billing')
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label" for="address">{{ __('locale.Address') }}</label>
                                            <input type="text" id="address" class="form-control" wire:model="address">
                                            @error('address') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label" for="zip">{{ __('locale.ZIP') }}</label>
                                            <input type="text" id="zip" class="form-control" wire:model="zip">
                                            @error('zip') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label" for="city">{{ __('locale.City') }}</label>
                                            <input type="text" id="city" class="form-control" wire:model="city">
                                            @error('city') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label" for="country">{{ __('locale.Country') }}</label>
                                            <input type="text" id="country" class="form-control" wire:model="country">
                                            @error('country') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label" for="email">{{ __('locale.Company Email') }}</label>
                                            <input type="email" id="email" class="form-control" wire:model="email">
                                            @error('email') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label" for="payment">{{ __('locale.Payment Method') }}</label>
                                            <input type="text" id="payment" class="form-control" wire:model="payment">
                                            @error('payment') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label" for="iban">IBAN</label>
                                            <input type="text" id="iban" class="form-control" wire:model="iban">
                                            @error('iban') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label" for="bic">BIC</label>
                                            <input type="text" id="bic" class="form-control" wire:model="bic">
                                            @error('bic') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-1">
                                            <label class="form-label" for="notes">{{ __('locale.Notes') }}</label>
                                            <textarea name="" id="notes" cols="40" rows="3" class="form-control" maxlength="500" wire:model="notes"></textarea>
                                            @error('notes') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <hr style="font-weight: bolder; width: 400px;left: 0;margin: 2rem 0;" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label" for="contact">{{ __('locale.Contact') }}</label>
                                            <input type="text" id="contact" class="form-control" wire:model="contact">
                                            @error('contact') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="contact_email">{{ __('locale.Contact Email') }}</label>
                                            <input type="text" id="contact_email" class="form-control" wire:model="contact_email">
                                            @error('contact_email') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                @elseif (!$isCreate && $tab == 'users')
                                    @livewire('company-users-component', ['company' => $company], key($company->id))
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                            @if(auth('web')->user()->hasRole('Admin'))
                                @if(!$modelId)
                                    <button class="btn btn-dark glow mb-sm-0 mr-sm-1 my-1" wire:loading.attr="disabled" wire:click="save('new')">
                                        <span>{{ __('locale.Save') }} & {{ __('locale.New') }}</span>
                                        <span style="margin-left: 5px;" wire:loading wire:target="save('new')">
                                            <div style="color: #F2F2F2;" class="la-line-scale la-sm">
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                            </div>
                                        </span>
                                    </button>
                                    &nbsp;
                                @endif
                                <button class="btn btn-outline-dark glow mb-sm-0 mr-sm-1 my-1" wire:loading.attr="disabled" wire:click="save('close')">
                                    <span>{{ __('locale.Save') }} & {{ __('locale.Close') }}</span>
                                    <span style="margin-left: 5px;" wire:loading wire:target="save('close')">
                                        <div style="color: #F2F2F2;" class="la-line-scale la-sm">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                        </div>
                                    </span>
                                </button>
                                &nbsp;
                            @endif
                            <button class="btn btn-secondary glow mb-1 mb-sm-0 mr-0 mr-sm-1 p-1 px-2 my-1 inline-block" data-bs-dismiss="modal" wire:click="$emit('refreshParent')">{{ __('locale.Cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div>
