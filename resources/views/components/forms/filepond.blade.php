{{-- You can change this template using File > Settings > Editor > File and Code Templates > Code > Laravel Ideal Blade View Component --}}
<div
    wire:ignore
    x-data
    x-init="() => {
        locale = '{!! config('app.locale') !!}';
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        const pond = FilePond.create($refs.input, {
            acceptedFileTypes: ['text/plain']
        });
        pond.setOptions({
            server: {
                process:(fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress);
                    window.addEventListener('uploadErrorInFile', e => {
                      if(e) {
                         error('An error occur with the file');
                      }
                    });
                },
                revert: (filename, load) => {
                    @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load);
                },
            },
            credit: false,
            labelIdle: '{{ __('locale.labelIdle') }}',
            labelInvalidField: '{{ __('locale.labelInvalidField') }}',
            labelFileWaitingForSize: '{{ __('locale.labelFileWaitingForSize') }}',
            labelFileSizeNotAvailable: '{{ __('locale.labelFileSizeNotAvailable') }}',
            labelFileLoading: '{{ __('locale.labelFileLoading') }}',
            labelFileLoadError: '{{ __('locale.labelFileLoadError') }}',
            labelFileProcessing: '{{ __('locale.labelFileProcessing') }}',
            labelFileProcessingComplete: '{{ __('locale.labelFileProcessingComplete') }}',
            labelFileProcessingAborted: '{{ __('locale.labelFileProcessingAborted') }}',
            labelFileProcessingError: '{{ __('locale.labelFileProcessingError') }}',
            labelFileProcessingRevertError: '{{ __('locale.labelFileProcessingRevertError') }}',
            labelFileRemoveError: '{{ __('locale.labelFileRemoveError') }}',
            labelTapToCancel: '{{ __('locale.labelTapToCancel') }}',
            labelTapToRetry: '{{ __('locale.labelTapToRetry') }}',
            labelTapToUndo: '{{ __('locale.labelTapToUndo') }}',
            labelButtonRemoveItem: '{{ __('locale.labelButtonRemoveItem') }}',
            labelButtonAbortItemLoad: '{{ __('locale.labelButtonAbortItemLoad') }}',
            labelButtonRetryItemLoad: '{{ __('locale.labelButtonRetryItemLoad') }}',
            labelButtonAbortItemProcessing: '{{ __('locale.labelButtonAbortItemProcessing') }}',
            labelButtonUndoItemProcessing: '{{ __('locale.labelButtonUndoItemProcessing') }}',
            labelButtonRetryItemProcessing: '{{ __('locale.labelButtonRetryItemProcessing') }}',
            labelButtonProcessItem: '{{ __('locale.labelButtonProcessItem') }}',
            labelMaxFileSizeExceeded: '{{ __('locale.labelMaxFileSizeExceeded') }}',
            labelMaxFileSize: '{{ __('locale.labelMaxFileSize') }}',
            labelMaxTotalFileSizeExceeded: '{{ __('locale.labelMaxTotalFileSizeExceeded') }}',
            labelMaxTotalFileSize: '{{ __('locale.labelMaxTotalFileSize') }}',
            labelFileTypeNotAllowed: '{{ __('locale.labelFileTypeNotAllowed') }}',
            fileValidateTypeLabelExpectedTypes: '{{ __('locale.fileValidateTypeLabelExpectedTypes') }}',
            imageValidateSizeLabelFormatError: '{{ __('locale.imageValidateSizeLabelFormatError') }}',
            imageValidateSizeLabelImageSizeTooSmall: '{{ __('locale.imageValidateSizeLabelImageSizeTooSmall') }}',
            imageValidateSizeLabelImageSizeTooBig: '{{ __('locale.imageValidateSizeLabelImageSizeTooBig') }}',
            imageValidateSizeLabelExpectedMinSize: '{{ __('locale.imageValidateSizeLabelExpectedMinSize') }}',
            imageValidateSizeLabelExpectedMaxSize: '{{ __('locale.imageValidateSizeLabelExpectedMaxSize') }}',
            imageValidateSizeLabelImageResolutionTooLow: '{{ __('locale.imageValidateSizeLabelImageResolutionTooLow') }}',
            imageValidateSizeLabelImageResolutionTooHigh: '{{ __('locale.imageValidateSizeLabelImageResolutionTooHigh') }}',
            imageValidateSizeLabelExpectedMinResolution: '{{ __('locale.imageValidateSizeLabelExpectedMinResolution') }}',
            imageValidateSizeLabelExpectedMaxResolution: '{{ __('locale.imageValidateSizeLabelExpectedMaxResolution') }}',
        });
        pond.on('processfile', (error, file) => {});
        pond.on('removefile', (error, file) => {
            window.livewire.emitTo('tenant.file-upload-component','clearErrorBag');
        });
        window.addEventListener('resetPond', e => {
          pond.removeFiles();
        });
    }">
    <input type="file" x-ref="input" />
</div>
