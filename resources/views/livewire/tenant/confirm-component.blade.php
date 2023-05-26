<div class="modal-dialog">
    <div class="modal-content">
        <div class="tariff-overlap-confirm-form">
            <div class="modal-header modal-header-add-user">
                <h5 class="modal-title" id="confirmTariffModalLabel">{{ __('locale.What rate do you want to set for the line?') }}</h5>
                <button type="button" wire:click="closeConfirmModal" class="btn-close">
                </button>
            </div>
            <form class="form-group pt-1" wire:submit.prevent="confirmConnection" method="POST">
                <div class="modal-body modal-body-add-user">
                    <div class="row">
                        @csrf
                        <div class="col-8">
                            <div class="form-group">
                                <label class="form-label" for="selectTariff">{{ __('locale.Choose tariff') }}</label>
                                <select class="form-control" id="selectTariff"
                                        wire:change="selectTariff($event.target.value)">
                                    @foreach($tariffs as $tariff)
                                        <option
                                            value="{{ $tariff->id }}" {{ !empty($connection->tariff->id) && $connection->tariff->id == $tariff->id ? 'selected' : '' }}>
                                            {{ $tariff->tariff_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('selectedTariff') <span class="error"
                                                               style="color: #ff0000">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div class="col">
                    </div>
                    <div class="col ml-auto d-flex justify-content-end">
                        <button wire:loading.attr="disabled" wire:click="confirmConnection" style="margin-right: 3px;" type="button"
                                class="btn btn-dark">
                            <span wire:loading.remove wire:target="confirmConnection">{{ __('locale.Save') }}</span>
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
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeConfirmModal"
                                data-dismiss="modal">{{ __('locale.Cancel') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

