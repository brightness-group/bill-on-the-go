<div class="col-12 col-sm-6">
    <div class="input-group input-group-merge">
        <span class="input-group-text" id="basic-addon-{{time()}}"><i class="bx bx-search"></i></span>
        <input type="search" class="form-control" wire:model.debounce.500ms="search" placeholder="{{__('locale.Search')}}" aria-label="{{__('locale.Search')}}" aria-describedby="basic-addon-{{time()}}" autocomplete="off" />
    </div>
</div>
