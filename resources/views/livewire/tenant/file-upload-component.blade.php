@section('title',__('locale.File Uploader'))

@section('custom_styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('frontend/css/table_file_uploaded_component_css.css?v=').time() }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/connection_edit_component_css.css?v=').time() }}">
    <style>
        .filepond--root, .filepond--drop-label {
            height: 100px;
            cursor: pointer;
        }

        .filepond--drop-label label {
            cursor: pointer;
        }

        .filepond--credits {
            visibility: hidden;
        }

        .picker {
            font-size: 10px;
            width: 200px;
        }

        .flatpickr-calendar {
            width: 220px;
        }

        .flatpickr-calendar, .flatpickr-weekday, .numInputWrapper {
            font-size: 10px;
        }

        .flatpickr-innerContainer, .flatpickr-rContainer, .dayContainer {
            width: 220px;
        }

        .dayContainer {
            min-width: 220px;
        }

        .flatpickr-day {
            width: fit-content;
            max-width: 30px;
            height: 32px;
            line-height: 36px;
            padding: 0 .1em;
        }
    </style>
@endsection

@push('header-script')
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
@endpush
<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>{{ __('locale.File Uploader') }}</h3>
        </div>
        <div class="card-content">
            <div class="card-body">
                <x-forms.filepond
                    wire:model="filepond"
                />
                @error('file_pond_error')
                <span class="error" style="color: red">{{ $message }}</span>
                @enderror
                <div class="d-flex justify-content-end">
                    <button class="btn btn-dark" wire:loading.attr="disabled"
                            {{$file ? '' : 'disabled="disabled"'}} wire:click="processedFile">{{ __('locale.Save') }}</button>
                    <button class="btn btn-outline-dark ms-1"
                            {{$file ? '' : 'disabled="disabled"'}} wire:click="cancel">{{ __('locale.Cancel') }}</button>
                </div>
            </div>
        </div>
        <div class="card-footer">

        </div>
    </div>
</div>

@section('custom_scripts')
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/de.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    <script>
        $(document).ready(function () {
            let $locale_sys = "{!! config('app.locale') !!}";

            window.addEventListener('fileUpload_edit_flatpickrs', event => {
                let start = event.detail.start;
                let end = event.detail.end;
                let startElem = $('#start_date');
                let endElem = $('#end_date');
                if ($locale_sys === 'de') {
                    flatpickr.localize(flatpickr.l10ns.de);
                    startElem.flatpickr({
                        enableTime: true,
                        time_24hr: true,
                        minuteIncrement: 1,
                        dateFormat: "d.m.Y H:i",
                        defaultDate: start,
                        locale: {
                            "locale": "de",
                            firstDayOfWeek: 1
                        },
                    });

                    endElem.flatpickr({
                        enableTime: true,
                        time_24hr: true,
                        minuteIncrement: 1,
                        dateFormat: "d.m.Y H:i",
                        defaultDate: end,
                        locale: {
                            "locale": "de",
                            firstDayOfWeek: 1
                        },
                    });
                } else {
                    startElem.flatpickr({
                            enableTime: true,
                            time_24hr: true,
                            minuteIncrement: 1,
                            dateFormat: "d.m.Y H:i",
                            defaultDate: start,
                            locale: {
                                firstDayOfWeek: 1
                            },
                        }
                    );
                    endElem.flatpickr({
                            enableTime: true,
                            time_24hr: true,
                            minuteIncrement: 1,
                            dateFormat: "d.m.Y H:i",
                            defaultDate: end,
                            locale: {
                                firstDayOfWeek: 1
                            },
                        }
                    );
                }
            });

            toastr.options = {
                positionClass: 'toast-top-center',
                showDuration: 1000,
                timeOut: 3000,
                hideDuration: 2000,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut'
            }
            window.addEventListener('showToastrSuccess', event => {
                toastr.success('', event.detail.message).css("width", "fit-content")
            })
            window.addEventListener('showToastrWarning', event => {
                toastr.warning('', event.detail.message).css("width", "fit-content")
            })
            window.addEventListener('focusErrorInput', event => {
                var $field = '#' + event.detail.field;
                $($field).focus()
            })
        })
    </script>
@endsection
