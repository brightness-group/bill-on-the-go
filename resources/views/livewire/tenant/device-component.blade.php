<div>
    <h4>{{ __('locale.Devices') }}</h4>

    <div class="row">
        <div class="col">
            <div class="text-end">
                <button wire:click="toggleDeviceDiv" class="btn btn-outline-dark" title="{{ __('locale.New Device') }}">
                    <i class="bx bx-plus me-sm-2"></i>
                    {{ __('locale.New Device') }}
                </button>
            </div>
        </div>
    </div>
    <div id="collapseDivForDevice" class="collapse collapse-div-for-device" wire:ignore.self>
        <form wire:submit.prevent="save">
            @csrf
            <div class="col-12 col-md-9 col-sm-9 d-flex pt-1 p-3">
                <div class="col display-inline-block">
                    <label for="alias">{{ __('locale.Alias') }}</label>
                    <input type="text" class="form-control" id="alias" wire:model="alias">
                    @error('alias') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                </div>

                &nbsp;&nbsp;

                <div class="col display-inline-block">
                    <label for="description">{{ __('locale.Description') }}</label>
                    <input type="text" class="form-control" id="description" wire:model="description">
                    @error('description') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                <div>
                    <button type="submit" class="btn btn-dark" wire:target="save">{{ __('locale.Save') }}</button>
                    <button type="button" class="btn btn-secondary" wire:click="cancel">{{ __('locale.Cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="row pt-1">
        <table class="table table-sm table-striped table-hover table-devices-component">
            <thead>
            <tr>
                <th></th>
                <th>{{ __('locale.Alias') }}</th>
                <th>{{ __('locale.Description') }}</th>
                <th colspan="2" class="text-center">{{ __('locale.Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @if(count($devices))
                @foreach($devices as $device)
                    <tr>
                        <td></td>
                        <td class="text-justify" wire:click="selectItem('{{ $device->id }}','update')" style="cursor: grabbing;">{{ ucfirst($device->alias) }}</td>
                        <td wire:click="selectItem('{{ $device->id }}','update')" style="cursor: grabbing;">{{ $device->description }}</td>
                        <td colspan="2" class="text-center">
                            <button class="btn btn-icon rounded-circle btn-light-secondary" wire:click="selectItem('{{ $device->id }}','update')" title="{{ __('locale.Edit') }}">
                                <i class="bx bx-edit"></i></button>
                            <button class="btn btn-icon rounded-circle btn-light-danger" wire:click="selectItem('{{ $device->id }}','delete')" title="{{ __('locale.Delete') }}">
                                <i class="bx bx-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="4" class="text-center">{{ __('locale.No results found') }}</td></tr>
            @endif
            </tbody>
        </table>
    </div>

    <!-- Delete Customer Modal -->
    <div class="modal fade" id="deviceModalDelete" tabindex="-1" aria-labelledby="deviceModalDeleteLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
                    <h5 class="modal-title" id="deviceModalDeleteLabel">{{ __('locale.Delete Device') }}</h5>
                    <button type="button" class="close" style="background-color: #c9c9c9" wire:click="closeDeviceModalDelete" aria-label="{{ __('locale.Close') }}">
                        <span aria-hidden="true"><strong>&times;</strong></span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>
                        {{ __('locale.Are you sure?') }}
                    </h3>

                    <h6>
                        @if (auth()->user()->locale == 'en')
                            {{ __('locale.Are you sure, you want to delete the device') }} 
                            {{ $alias_text }}
                        @else
                            {{ __('locale.Are you sure, you want to delete the device') }} 
                            {{ $alias_text }} 
                            {{ __('locale.Want to delete?') }}
                        @endif
                    </h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeDeviceModalDelete">{{ __('locale.Cancel') }}</button>
                    <button type="button" class="btn btn-dark" wire:click="destroy" wire:loading.attr="disabled">{{ __('locale.Yes') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

