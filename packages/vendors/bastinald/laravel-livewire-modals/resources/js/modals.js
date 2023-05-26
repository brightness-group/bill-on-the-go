let modalsElement = document.getElementById('laravel-livewire-modals');

$(modalsElement).on('hidden.bs.modal', () => {
    Livewire.emit('resetModal');
});

Livewire.on('showBootstrapModal', () => {
    $(modalsElement).modal('show');
});

Livewire.on('hideModal', () => {
    $(modalsElement).modal('hide');
});
