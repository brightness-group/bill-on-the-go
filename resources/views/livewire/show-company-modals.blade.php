<!-- Delete Photo Modal -->
<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteLogoModalLabel">{{ __('locale.Totally delete photo profile') }}</h5>
            <button type="button" class="close" wire:click="toggleShowCompanyComponentModal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <h3>{{ __('locale.Are you sure?') }}</h3>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="toggleShowCompanyComponentModal">{{ __('locale.Cancel') }}</button>
            <button type="button" class="btn btn-primary" wire:click="deletePhoto">{{ __('locale.Yes') }}</button>
        </div>
    </div>
</div>
