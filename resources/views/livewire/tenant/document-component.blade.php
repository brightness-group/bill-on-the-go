@section('title', __('locale.Documents'))
<div class="document">
    <h4>{{ __('locale.Documents') }}</h4>

    <div class="row">
        <div class="col">
            <div class="text-end">
                <button wire:click="toggleDocumentDiv" class="btn btn-outline-dark" title="{{ __('locale.Upload Document') }}">
                    <i class="bx bx-plus me-sm-2"></i>
                    {{ __('locale.Upload Document') }}
                </button>
            </div>
        </div>
    </div>
    <div id="collapseDivForDocument" class="collapse collapse-div-for-device" wire:ignore.self>
        <form wire:submit.prevent="save">
            @csrf
            
            <div class="col-12 col-md-9 col-sm-9 d-flex pt-1 p-3">
                <div class="col display-inline-block">
                    <input class="form-control" type="file"  id="upload_{{ $iteration }}" wire:model="file"  >
                    @error('file') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                <div>
                    <button type="submit" class="btn btn-dark" wire:target="save">{{ __('locale.Save') }}</button>
                    <button type="button" class="btn btn-secondary" wire:click="cancel">{{ __('locale.Cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="row pt-1">
        <table class="table table-sm table-striped table-hover table-devices-component">
            <thead>
            <tr>
                <th>{{ __('locale.File Name') }}</th>
                <th class="text-center">{{ __('locale.From') }}</th>
                <th class="text-center">{{ __('locale.Date') }}</th>
                <th class="text-center">{{ __('locale.Size') }}</th>
                <th class="text-center">{{ __('locale.Filetype') }}</th>
                <th colspan="2" class="text-center">{{ __('locale.Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @if(count($documents))
                @foreach($documents as $document)
                    <tr>
                        <td><a href="javascript:void(0);" role="button" wire:click="downloadFile('{{ $document->id }}')">{{ pathinfo($document->file, PATHINFO_FILENAME) }}</a></td>
                        <td class="text-center">{{$document->user->name}}</td>
                        <td class="text-center">{{$document->created_at->format('d.m.Y')}}</td>
                        <td class="text-center">{{$document->file_size}}</td>
                        <td class="text-center">{{StrtoUpper(pathinfo($document->file, PATHINFO_EXTENSION))}}</td>
                        <td class="text-center"><button class="btn btn-icon rounded-circle btn-light-danger" wire:click="selectItem('{{ $document->id }}','delete')" title="{{ __('locale.Delete') }}">
                                <i class="bx bx-trash"></i></button></td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="6" class="text-center">{{ __('locale.No results found') }}</td></tr>
            @endif
            </tbody>
        </table>
    </div>

    <!-- Delete Customer Modal -->
    <div class="modal fade" id="DocumentModalDelete" tabindex="-1" aria-labelledby="DocumentModalDeleteLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
                    <h5 class="modal-title" id="DocumentModalDeleteLabel">{{ __('locale.Delete Document') }}</h5>
                    <button type="button" class="close" style="background-color: #c9c9c9" wire:click="closeDocumentModalDelete" aria-label="{{ __('locale.Close') }}">
                        <span aria-hidden="true"><strong>&times;</strong></span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>
                        {{ __('locale.Are you sure?') }}
                    </h3>

                    <h6>
                        {{ __('locale.Are you sure, you want to delete the document',['filename' => $doc_name]) }} 
                    </h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeDocumentModalDelete">{{ __('locale.Cancel') }}</button>
                    <button type="button" class="btn btn-dark" wire:click="destroy" wire:loading.attr="disabled">{{ __('locale.Yes') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

