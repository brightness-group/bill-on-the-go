<div class="col-6 d-flex text-vertical-center">
    <label for="drp-entries">
        {{ __('locale.Show') }} &nbsp;
    </label>

    <select class="form-control drp-entries" id="drp-entries" wire:model="limit">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="75">75</option>
        <option value="100">100</option>
    </select>

    <label>
        &nbsp; {{ __('locale.Entries') }}
    </label>
</div>
