<div class="modal-dialog">
    <div class="modal-content">
        <div class="delete-todo">
            <div class="modal-header modal-header-add-device box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
                <h5 class="modal-title" id="deleteTodoModalLabel">{{ __('locale.Delete Todo') }}</h5>
                <button type="button" wire:click="$emit('hideModal', 'tenant.todo-delete-component')" class="close"
                        style="background-color: #c9c9c9;width: 2rem;height: 2rem;" data-dismiss="modal" aria-label="Close">
                    <div class="d-flex justify-content-center align-content-center">
                        <span aria-hidden="true"><strong>&times;</strong></span>
                    </div>
                </button>
            </div>
            <div class="modal-body">
                <h3>{{ __('locale.Are you sure?') }}</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="$emit('hideModal', 'tenant.todo-delete-component')">{{ __('locale.Cancel') }}</button>
                <button type="button" class="btn btn-primary" wire:click="destroy">{{ __('locale.Yes') }}</button>
            </div>
        </div>
    </div>
</div>
