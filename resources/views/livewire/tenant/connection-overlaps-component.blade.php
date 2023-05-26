<div>
    <div class="modal-header">
        <h5 class="modal-title" id="overlapsModalLabel">{{ __('locale.Overlaps') }}</h5>
        <button wire:loading.attr="disabled" type="button" class="close" style="background-color: #c9c9c9" wire:click="closedOverlapsModal" aria-label="Close">
            <span wire:loading.remove wire:target="closedOverlapsModal" aria-hidden="true"><strong>&times;</strong></span>
            <x-loading.ball-spin-clockwise wire:loading wire:target="closedOverlapsModal" class="la-ball-spin-clockwise la-sm" style="color: #495057;"></x-loading.ball-spin-clockwise>
        </button>
    </div>
    <div class="modal-body">
        <table class="table table-striped table-modal-overlaps">
            <thead>
            <tr>
                @foreach($headers as $key => $value)
                    <th>
                        <span>{{ __('locale.'.$value) }}</span>
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @if(!is_null($collectionOverlaps))
                @foreach($collectionOverlaps as $collection)
                <tr style="background-color: hsl(356, 76%, 95%);">
                    <td>{{ $collection->username }}</td>
                    <td>{{ $collection->groupname }}</td>
                    <td>{{ $collection->devicename }}</td>
                    <td class="text-nowrap">@datetime($collection->start_date->setTimezone(config('site.default_timezone')))</td>
                    <td class="text-nowrap">@datetime($collection->end_date->setTimezone(config('site.default_timezone')))</td>
                    <td>{{ $collection->price ? $collection->price.' €' : '-' }}</td>
                    <td>
                        <fieldset>
                            <div class="checkbox" wire:loading.remove wire:target="changeBillOverlapModal('{{ $collection->id }}')">
                                <input type="checkbox" wire:click="changeBillOverlapModal('{{ $collection->id }}')" id="checkbox-bill-overlap-modal-{{ $collection->id }}" class="checkbox-input"
                                       style="border-color: #495057;" {{ $collection->billing_state == 'Bill' ? 'checked' : '' }}>
                                <label for="checkbox-bill-overlap-modal-{{ $collection->id }}"></label>
                            </div>
                            <div wire:loading wire:target="changeBillOverlapModal('{{ $collection->id }}')">
                                <div class="la-ball-spin-clockwise la-sm" style="color: #495057;">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                        </fieldset>
                    </td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="{{ count($headers) }}">{{ __('locale.No results found') }}</td></tr>
            @endif
            </tbody>
        </table>
        <div class="row justify-content-end">
            <div class="d-flex p-1">
                <fieldset>
                    <div class="checkbox">
                        <input type="checkbox" class="checkbox-input" id="checkbox1" wire:model="solveConflict">
                        <label for="checkbox1">{{ __('locale.Resolve conflict') }}</label>
                    </div>
                </fieldset>
            </div>
            <div class="d-flex p-1">
                <button {{ $solveConflict ? '' : 'disabled' }} class="btn btn-sm btn-dark" wire:loading.attr="disabled" wire:click.prevent="solveConflict">{{ __('locale.Save') }}</button>
            </div>
        </div>
    </div>
</div>
