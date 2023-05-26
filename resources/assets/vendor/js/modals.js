
/*
* Laravel livewire modals js
*
* see more: https://github.com/bastinald/laravel-livewire-modals
* */
let modalsElement = document.getElementById('laravel-livewire-modals');

$(modalsElement).on('shown.bs.modal', () => {
    feather.replace();
});

$(modalsElement).on('hidden.bs.modal', () => {
    Livewire.emit('resetModal');
});

Livewire.on('showBootstrapModal', () => {
    $(modalsElement).modal('show');
});

Livewire.on('hideModal', () => {
    $(modalsElement).modal('hide');
});
