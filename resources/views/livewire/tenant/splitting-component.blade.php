<div>
    <div class="modal-header">
        <h5 class="modal-title" id="splittingModalLabel">{{ __('locale.Splitting Connection') }}</h5>
        <button wire:loading.attr="disabled" type="button" class="close" style="background-color: #c9c9c9"
                aria-label="Close" wire:click="closeSplittingModal">
            <span wire:loading.remove wire:target="closeSplittingModal"
                  aria-hidden="true"><strong>&times;</strong></span>
            <x-loading.ball-spin-clockwise wire:loading wire:target="closeSplittingModal"
                                           class="la-ball-spin-clockwise la-sm"
                                           style="color: #495057;"></x-loading.ball-spin-clockwise>
        </button>
    </div>
    <div class="modal-body">
        <form wire:submit.prevent="splitConnection">
            <div class="row">
                <div class="col-md-6 col-12 border-right">
                    <div class="col-12 mb-3">
                        <h6>{{ __('locale.First Split') }}</h6>
                    </div>
                    <div class="col-12">
                        <div class="form-label-group">
                            <input type="text" id="firstStart-splitting-datetime" class="form-control"
                                   value="{{ $connSplittedOneStartTime ? $connSplittedOneStartTime->format('d.m.Y H:i') : null }}"
                                   readonly>
                            <label for="firstStart-splitting-datetime">{{ __('locale.Origin Start Date') }}</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-label-group">
                            <input type="text" id="firstEnd-splitting-datetime" class="form-control"
                                   value="{{ $connSplittedOneEndTime ? $connSplittedOneEndTime->format('d.m.Y H:i') : null }}"
                                   readonly>
                            <label for="firstEnd-splitting-datetime">{{ __('locale.Split End Date') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="col-12 mb-3">
                        <h6>{{ __('locale.Second Split') }}</h6>
                    </div>
                    <div class="col-12">
                        <div class="form-label-group">
                            <input type="text" id="secondStart-splitting-datetime" class="form-control"
                                   value="{{ $connSplittedTwoStartTime ? $connSplittedTwoStartTime->format('d.m.Y H:i') : null }}"
                                   readonly>
                            <label for="secondStart-splitting-datetime">{{ __('locale.Split Start Date') }}</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-label-group">
                            <input type="text" id="secondEnd-splitting-datetime" class="form-control"
                                   value="{{ $connSplittedTwoEndTime ? $connSplittedTwoEndTime->format('d.m.Y H:i') : null }}"
                                   readonly>
                            <label for="secondEnd-splitting-datetime">{{ __('locale.Origin End Date') }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-end align-items-end pt-2">
                <div class="col-md-6 col-12 d-flex justify-content-end mr-1">
                    <button type="submit" class="btn btn-dark"
                            {{$connSplittedOneStartTime && $connSplittedOneEndTime && $connSplittedTwoStartTime && $connSplittedTwoEndTime  ? '' : 'disabled'}}
                            wire:loading.attr="disabled" style="margin-right: 3px;">{{ __('locale.Split') }}</button>
                    <button type="button" class="btn btn-outline-secondary" wire:loading.attr="disabled"
                            wire:click="closeSplittingModal">{{ __('locale.Cancel') }}</button>
                </div>
            </div>
        </form>

    </div>
</div>
