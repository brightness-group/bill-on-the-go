@section('title', __('locale.Tariffs'))

<div>
    <div class="row">
        <div class="{{!empty($currentRouteName) && $currentRouteName == 'pages.tariffs' ? 'col-2' : 'col-4'}}">
            <h4>{{ __('locale.Tariffs') }}
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#infoModel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                    </svg>
                </a>
            </h4>
        </div>
    </div>
    <hr>
    <div class="bs-stepper-content form-check custom-option custom-option-icon collapse collapse-div-for-tariff p-3 mb-4"
        id="collapseDivForTariff" wire:ignore.self wire:key="tariff-collapsible-div">
        <div class="row">
            <div class="col-sm-6">
                <label class="form-label">{{ __('locale.Type:') }}</label>
                <br />
                @if(empty($selectedItem))
                    <button class="btn @if($tabTable == "active") btn-dark @else btn-outline-dark @endif btn-sm"
                            type="button" wire:click="$set('tabTable','active')">
                        {{ $tabTable == "active" ? __('locale.Active') : __('locale.Archieved')}}
                    </button>
                @else
                    <button class="btn @if($tabTable == "active") btn-dark @else btn-outline-dark @endif btn-sm"
                            type="button" @if($tabTable=='active') wire:click="$set('tabTable','archieved')"
                            @elseif($tabTable=='archieved') wire:click="$set('tabTable','active')" @endif>
                        {{ $tabTable == "active" ? __('locale.Active') : __('locale.Archieved')}}
                    </button>
                @endif
            </div>

            <div class="col-sm-6">
                <label class="form-label">{{ __('locale.Colour') }}</label>
                <br />
                <input type="hidden" id="favcolor" wire:model="color" />
                <div class="monolith col col-sm-3 col-lg-2">
                    <div id="color-picker-monolith"></div>
                </div>
            </div>
        </div>

        <br />

        <div class="row">
            <div class="col-sm-6">
                <label for="tariff_name">{{ __('locale.Name') }}</label>
                <input type="text" class="form-control" id="tariff_name" wire:model.lazy="tariff_name">
                @error('tariff_name') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
            </div>

            <div class="col-sm-6">
                <label for="price">{{ __('locale.Price') }}</label>
                <input type="text" class="form-control" id="price" data-type="currency" placeholder="0,00"
                    wire:model="price">{{-- --}}
                @error('price') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
            </div>
        </div>

        <br />

        <div class="row">
            <div class="col-sm-12">
                @if ($selected_days['monday'] == false)
                <label class="btn btn-sm rounded-pill btn-outline-dark" for="btn-check-monday">
                    @else
                    <label class="btn btn-sm rounded-pill btn-dark" for="btn-check-monday">
                        @endif

                        <input type="checkbox" class="btn-check" id="btn-check-monday"
                            wire:click="$set('selected_days.monday', {{ !$selected_days['monday'] }})"
                            autocomplete="off" hidden>
                        {{ __('locale.Mo') }}
                    </label>

                    @if ($selected_days['tuesday'] == false)
                    <label class="btn btn-sm rounded-pill btn-outline-dark" for="btn-check-tuesday">
                        @else
                        <label class="btn btn-sm rounded-pill btn-dark" for="btn-check-tuesday">
                            @endif

                            <input type="checkbox" class="btn-check" id="btn-check-tuesday"
                                wire:click="$set('selected_days.tuesday', {{ !$selected_days['tuesday'] }})"
                                autocomplete="off" hidden>
                            {{ __('locale.Tu') }}
                        </label>

                        @if ($selected_days['wednesday'] == false)
                        <label class="btn btn-sm rounded-pill btn-outline-dark" for="btn-check-wednesday">
                            @else
                            <label class="btn btn-sm rounded-pill btn-dark" for="btn-check-wednesday">
                                @endif
                                <input type="checkbox" class="btn-check" id="btn-check-wednesday"
                                    wire:click="$set('selected_days.wednesday', {{ !$selected_days['wednesday'] }})"
                                    autocomplete="off" hidden>
                                {{ __('locale.We') }}
                            </label>

                            @if ($selected_days['thursday'] == false)
                            <label class="btn btn-sm rounded-pill btn-outline-dark" for="btn-check-thursday">
                                @else
                                <label class="btn btn-sm rounded-pill btn-dark" for="btn-check-thursday">
                                    @endif
                                    <input type="checkbox" class="btn-check" id="btn-check-thursday"
                                        wire:click="$set('selected_days.thursday', {{ !$selected_days['thursday'] }})"
                                        autocomplete="off" hidden>
                                    {{ __('locale.Th') }}
                                </label>

                                @if ($selected_days['friday'] == false)
                                <label class="btn btn-sm rounded-pill btn-outline-dark" for="btn-check-friday">
                                    @else
                                    <label class="btn btn-sm rounded-pill btn-dark" for="btn-check-friday">
                                        @endif
                                        <input type="checkbox" class="btn-check" id="btn-check-friday"
                                            wire:click="$set('selected_days.friday', {{ !$selected_days['friday'] }})"
                                            autocomplete="off" hidden>
                                        {{ __('locale.Fr') }}
                                    </label>

                                    @if ($selected_days['saturday'] == false)
                                    <label class="btn btn-sm rounded-pill btn-outline-dark" for="btn-check-saturday">
                                        @else
                                        <label class="btn btn-sm rounded-pill btn-dark" for="btn-check-saturday">
                                            @endif
                                            <input type="checkbox" class="btn-check" id="btn-check-saturday"
                                                wire:click="$set('selected_days.saturday', {{ !$selected_days['saturday'] }})"
                                                autocomplete="off" hidden>
                                            {{ __('locale.Sa') }}
                                        </label>

                                        @if ($selected_days['sunday'] == false)
                                        <label class="btn btn-sm rounded-pill btn-outline-dark" for="btn-check-sunday">
                                            @else
                                            <label class="btn btn-sm rounded-pill btn-dark" for="btn-check-sunday">
                                                @endif
                                                <input type="checkbox" class="btn-check" id="btn-check-sunday"
                                                    wire:click="$set('selected_days.sunday', {{ !$selected_days['sunday'] }})"
                                                    autocomplete="off" hidden>
                                                {{ __('locale.Su') }}
                                            </label>

                                            @error('booleanSelectedDay') <span class="error" style="color: #ff0000">{{
                                                $message }}</span> @enderror
            </div>
        </div>

        <br />

        <div class="row">
            <div class="col-sm-4">
                <label class="form-label">
                    <span class="badge bg-dark">{{ __('locale.Start at') }}</span>
                </label>
                <input wire:model.lazy="initial_time" type="text" class="form-control time" id="initial_time"
                    placeholder="00:00">
                @error('initial_time') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
            </div>

            <div class="col-sm-4">
                <label class="form-label">
                    <span class="badge bg-dark">{{ __('locale.End at') }}</span>
                </label>
                <input wire:model.lazy="end_time" type="text" class="form-control time" id="end_time"
                    placeholder="23:59">
                @error('end_time') <span class="error" style="color: #ff0000;">{{ $message }}</span> @enderror
            </div>

            <div class="col-sm-4">
                <label>
                    <span class="text-muted">{{ __('locale.Time Interval') }}</span>
                </label>
                <input type="text" maxlength="2" class="form-control text-center" wire:model="interval">
                @error('interval') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
            </div>
        </div>

        <br />

        <div class="row">
            <div class="col-sm-4">
                <label class="form-label">
                    <span class="badge bg-dark">{{ __('locale.From') }}</span>
                </label>

                <input wire:model="start_period" type="text" class="form-control pickadate_start"
                    placeholder="{{ __('locale.Select') }}" onchange="this.dispatchEvent(new InputEvent('input'))">
                @error('start_period') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
            </div>

            <div class="col-sm-4">
                <label class="form-label">
                    <span class="badge bg-dark">{{ __('locale.To') }}</span>
                </label>

                <input {{ $permanent ? 'disabled' : '' }} @if($permanent) style="background-color: #B0BED9;" @endif
                    wire:model="end_period" type="text" class="form-control pickadate_end"
                    placeholder="{{ __('locale.Select') }}" onchange="this.dispatchEvent(new InputEvent('input'))">
                @error('end_period') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
            </div>

            <div class="col-sm-4">
                <br />
                <input type="checkbox" class="custom-control-input form-check-input" name="customCheck"
                    id="customCheck2" wire:model="permanent">
                <label>
                    <small class="text-muted">{{ __('locale.Permanent') }}</small>
                </label>
            </div>
        </div>

        <br />

        <div class="row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-dark" wire:click="store" wire:loading.attr="disabled">
                    <span>{{ __('locale.Save') }}</span>
                    <span style="margin-left: 5px;" wire:loading wire:target="store">
                        <div style="color: #F2F2F2;" class="la-line-scale la-sm">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </span>
                </button>

                <button type="button" class="btn btn-secondary" wire:click="cancel">{{ __('locale.Cancel') }}</button>
            </div>
        </div>
    </div>

    <script>
        document.onload = function() {
            $('#collapseDivForTariff').on('hidden.bs.collapse', function () {
                window.livewire.emitTo('tenant.tariff-component','cleanVars');
            });
        };
    </script>

    <div class="mt-2 d-flex justify-content-between">
        <div>
            @if ($tabTable == 'active')
            <button class="btn btn-dark" wire:click="$set('tabTable','active')"><strong>{{ __('locale.Active')
                    }}</strong></button>
            <button class="btn btn-outline-dark" {{ !empty($selectedItem) ? 'disabled' : '' }}
                wire:click="$set('tabTable','archieved')">{{ __('locale.Archieved') }}</button>
            @else
            <button class="btn btn-outline-dark" wire:click="$set('tabTable','active')">{{ __('locale.Active')
                }}</button>
            <button class="btn btn-dark" wire:click="$set('tabTable','archieved')"><strong>{{ __('locale.Archieved')
                    }}</strong></button>
            @endif
        </div>

        <div>
            <button wire:click="toggleDiv" class="btn btn-outline-dark" title="{{ __('locale.New Tariff') }}">
                <i class="bx bx-plus me-sm-2"></i>
                {{ __('locale.New Tariff') }}
            </button>
        </div>
    </div>

    <hr />

    <br>
    <!-- Archieve Tariff Modal -->
    <div class="modal fade" id="tariffModalDelete" tabindex="-1" aria-labelledby="tariffModalDeleteLabel"
        aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tariffModalDeleteLabel">{{ __('locale.File Tariff') }}</h5>
                    <button type="button" class="close" wire:click="closeTariffModalDelete"
                        aria-label="{{ __('locale.Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>
                        {{ __('locale.Are you sure want to archieve') }} {{ $tariffName }} {{ __('locale.Post archieve')
                        }} ?
                    </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeTariffModalDelete">{{
                        __('locale.Cancel') }}</button>
                    <button type="button" class="btn btn-dark" wire:click="destroy" wire:loading.attr="disabled">{{
                        __('locale.Yes') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive text-nowrap" style="overflow-x: unset;">
        <table class="table clickble table-hover" wire:key="table-active-archieve-{{ time() }}">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-justify">{{ __('locale.Name') }}</th>
                    <th>{{ __('locale.Days') }}</th>
                    <th>{{ __('locale.From') }} - {{ __('locale.To') }}</th>
                    <th class="text-center">IT <small>({{ __('locale.min') }})</small></th>
                    <th>{{ __('locale.Price') }}</th>
                    <th>{{ __('locale.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @if($tabTable == 'active')
                @if(count($active_tariffs))

                @foreach($active_tariffs as $tariff)
                <tr class="{{ $tariff->overlap_status == true ? 'table-danger' : '' }}">
                    <td class="text-right" wire:click="selectItem({{ $tariff->id }},'update')">
                        @if($tariff->color)
                        <span class="bullet bullet-sm bullet-primary"
                            style="background-color: {{ $tariff->color }}"></span>
                        @endif
                    </td>
                    <td class="text-justify" wire:click="selectItem({{ $tariff->id }},'update')">
                        {{ $tariff->tariff_name }}
                    </td>
                    <td wire:click="selectItem({{ $tariff->id }},'update')">
                        @php
                            $days_data = $tariff->getSelectedDaysAttribute($tariff->getAttributes()['selected_days'], true);
                        @endphp
                        @foreach($days_data as $key => $day)
                            @if($day)
                                @switch($key)
                                    @case('monday')
                                        <span>{{ __('locale.Mo') }}</span>
                                        @break
                                    @case('tuesday')
                                        <span>{{ __('locale.Tu') }}</span>
                                        @break
                                    @case('wednesday')
                                        <span>{{ __('locale.We') }}</span>
                                        @break
                                    @case('thursday')
                                        <span>{{ __('locale.Th') }}</span>
                                        @break
                                    @case('friday')
                                        <span>{{ __('locale.Fr') }}</span>
                                        @break
                                    @case('saturday')
                                        <span>{{ __('locale.Sa') }}</span>
                                        @break
                                    @case('sunday')
                                        <span>{{ __('locale.Su') }}</span>
                                        @break
                                    @default
                                        <span>{{ $day }}</span>
                                        @break
                                @endswitch
                            @endif
                        @endforeach
                    </td>
                    <td wire:click="selectItem({{ $tariff->id }},'update')">{{ ($tariff->initial_time) }} - {{
                        $tariff->end_time }}</td>
                    <td class="text-center" wire:click="selectItem({{ $tariff->id }},'update')"
                        wire:click="selectItem({{ $tariff->id }},'update')">{{ $tariff->interval }}</td>
                    <td wire:click="selectItem({{ $tariff->id }},'update')">{{ ($tariff->price).' €' }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);"
                                    wire:click="selectItem({{ $tariff->id }},'update')" title="{{ __('locale.Edit') }}">
                                    <i class="bx bx-edit-alt me-2"></i>
                                    {{ __('locale.Edit') }}
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    wire:click="selectItem({{ $tariff->id }}, 'delete', '{{ $tariff->tariff_name }}')"
                                    title="{{ __('locale.Archive') }}">
                                    <i class="bx bx-archive me-2"></i>
                                    {{ __('locale.Archive') }}
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="7" class="text-center">{{ __('locale.There is no actives tariffs') }}</td>
                </tr>
                @endif
                @elseif($tabTable == 'archieved')
                @if(count($archieved_tariffs))
                @foreach($archieved_tariffs as $tariff)
                <tr>
                    <td class="text-right">
                        @if($tariff->color)
                        <span class="bullet bullet-sm bullet-primary"
                            style="background-color: {{ $tariff->color }}"></span>
                        @endif
                    </td>
                    <td style="padding: 1rem 1rem">{{ $tariff->tariff_name }}</td>
                    <td>
                        @foreach($tariff->selected_days as $key => $day)
                        @if($day)
                        @switch($key)
                        @case('monday')
                        <span>{{ __('locale.Mo') }}</span>
                        @break
                        @case('tuesday')
                        <span>{{ __('locale.Tu') }}</span>
                        @break
                        @case('wednesday')
                        <span>{{ __('locale.We') }}</span>
                        @break
                        @case('thursday')
                        <span>{{ __('locale.Th') }}</span>
                        @break
                        @case('friday')
                        <span>{{ __('locale.Fr') }}</span>
                        @break
                        @case('saturday')
                        <span>{{ __('locale.Sa') }}</span>
                        @break
                        @case('sunday')
                        <span>{{ __('locale.Su') }}</span>
                        @break
                        @endswitch
                        @endif
                        @endforeach
                    </td>
                    <td>{{ ($tariff->initial_time) }} - {{ $tariff->end_time }}</td>
                    <td class="text-center">{{ $tariff->interval }}</td>
                    <td>{{ ($tariff->price).' €' }}</td>
                    <td>
                        <button class="btn btn-icon rounded-circle btn-success"
                            wire:click="activateTariff({{ $tariff->id }})" title="{{ __('locale.Activate') }}">
                            <i class="bx bx-undo"></i></button>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="7" class="text-center">{{ __('locale.There is no archieved tariffs') }}</td>
                </tr>
                @endif
                @endif
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="infoModel" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalToggleLabel">{{ __('locale.Infobox') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-12">
                        <div class="tariff-infobox right">
                            <p>
                            @if(!empty($currentRouteName) && $currentRouteName == 'pages.tariffs')
                                {{__('locale.customer-info-line1')}}
                                <br>
                                {{__('locale.customer-info-line2')}}
                                <br>
                                <a href="{{route('customers.list')}}">{{__('locale.customer-info-link-text')}}</a>
                            @else
                                {{__('locale.tariff-info-line1')}}
                                <br>
                                {{__('locale.tariff-info-line2')}}
                                <br>
                                <a href="{{route('pages.tariffs')}}">{{__('locale.tariff-info-link-text')}}</a>
                            @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
