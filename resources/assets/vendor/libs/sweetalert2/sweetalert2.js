import * as SwalPlugin from 'sweetalert2/dist/sweetalert2';

const Swal = SwalPlugin.mixin({
  buttonsStyling: false,
  customClass: {
    confirmButton: 'btn btn-dark',
    cancelButton: 'btn btn-outline-secondary',
    denyButton: 'btn btn-label-secondary'
  }
});

export { Swal };
