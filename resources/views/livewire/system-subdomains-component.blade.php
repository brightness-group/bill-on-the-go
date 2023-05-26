@section('title',__('locale.Subdomains'))

@section('custom_css')
    <link rel="stylesheet" href="{{asset('frontend/css/subdomains-component_css.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/loading_states_awesome.css')}}">
@endsection

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3><strong>{{ __('locale.Subdomains') }}</strong></h3>
    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-6">
                    @if(auth()->user()->hasRole('Admin'))
                        <button type="button" class="btn btn-outline-dark" wire:click="openFormSubdomainModal"><i class="bx bx-plus"></i>
                            <span>{{ __('locale.New') }}</span>
                        </button>
                    @endif
                </div>
                @livewire('search-field-component', ['search' => $search])
            </div>

            <br />

            <!-- Form User Modal -->
            <div wire:ignore.self class="modal fade" id="formSubdomainModal" tabindex="-1" aria-labelledby="formSubdomainModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            @if(!$selectedItem)
                                <h5 class="modal-title" id="formSubdomainModalLabel">{{ __('locale.New Subdomain')  }}</h5>
                            @else
                                <h5 class="modal-title" id="formSubdomainModalLabel">{{ __('locale.Edit Subdomain') }}</h5>
                            @endif
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                    wire:click="closeFormSubdomainModal"></button>
                        </div>
                        @livewire('create-edit-subdomain-form')
                    </div>
                </div>
            </div>

            <!-- Delete Company's User Modal -->
            <div wire:ignore.self class="modal fade" id="userDeleteModal" tabindex="-1" aria-labelledby="userDeleteModal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
                            <h5 class="modal-title" id="userDeleteModal">{{ __('locale.Delete Subdomain') }}</h5>
                            <button type="button" class="close" style="background-color: #c9c9c9" wire:click="closeSubdomainDeleteModal" aria-label="Close">
                                <span aria-hidden="true"><strong>&times;</strong></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h3>{{ __('locale.Are you sure?') }}</h3>
                        </div>
                        <div class="modal-footer box-shadow-1">
                            <button type="button" class="btn btn-secondary" wire:click="closeSubdomainDeleteModal">{{ __('locale.Cancel') }}</button>
                            <button type="button" class="btn btn-primary" wire:click="destroy" wire:loading.attr="disabled">{{ __('locale.Yes') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table clickble table-hover table-subdomains">
                <thead>
                <tr>
                    <th class="text-justify">{{ __('locale.Subdomain') }}</th>
                    <th class="text-justify">{{ __('locale.Target') }}</th>
                    <th class="text-justify">{{ __('locale.Description') }}</th>
                    <th>{{ __('locale.CREATED AT') }}</th>
                    <th colspan="3">{{ __('locale.ACTIONS') }}</th>
                </tr>
                </thead>
                <tbody>
                @if( count($subdomains) )
                    @foreach($subdomains as $subdomain)
                        <tr>
                            <td>{{ $subdomain->subdomain }}</td>
                            <td><a href="{{ $subdomain->target }}" target="_blank">{{ $subdomain->target }}</a></td>
                            <td>{{ $subdomain->description }}</td>
                            <td class="text-center">@datetime($subdomain->created_at)</td>
                            <td class="text-center">
                                <div class="display-inline-block">
                                    @if(auth('web')->user()->hasRole('Admin'))
                                        <button wire:click="selectItem({{ $subdomain->id }}, 'update')" class="btn btn-icon rounded-circle btn-light-secondary" title="{{ __('locale.Edit') }}">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <button wire:click="selectItem({{ $subdomain->id }}, 'delete')" class="btn btn-icon rounded-circle btn-light-danger" title="{{ __('locale.Delete') }}">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        @if( $search == '')
                            <td colspan="6" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There are no subdomains') }}</p></td>
                        @else
                            <td colspan="6" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There are no subdomains') }} "{{ $search }}"</p></td>
                        @endif
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <div class="card-footer" style="float: right;">
            {{ $subdomains->links() }}
        </div>
    </div>
</div>

@section('custom_scripts')

    <script>

        $(document).ready(function() {
            $("#formSubdomainModal").on('hidden.bs.modal', function(){
                livewire.emit('forcedCloseUserModal');
            });
            $("#userDeleteModal").on('hidden.bs.modal', function(){
                livewire.emit('forcedCloseUserModal');
            });
            toastr.options = {
                positionClass: 'toast-top-center',
                showDuration: 1000,
                timeOut: 3000,
                hideDuration: 2000,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut',
                preventDuplicates: false,
            }
            window.addEventListener('showToastrSuccess', event => {
                toastr.success('',event.detail.message).css("width","fit-content")
            })
            window.addEventListener('showToastrError', event => {
                toastr.error('',event.detail.message).css("width","fit-content")
            })
            window.addEventListener('showToastrDelete', event => {
                toastr.warning('',event.detail.message).css("width","fit-content")
            })
        });

        window.addEventListener('openFormSubdomainModal', event => {
            $("#formSubdomainModal").modal('show');
        })

        window.addEventListener('closeFormSubdomainModal', event => {
            $("#formSubdomainModal").modal('hide');
        })

        window.addEventListener('openSubdomainDeleteModal', event => {
            $("#userDeleteModal").modal('show');
        })

        window.addEventListener('closeSubdomainDeleteModal', event => {
            $("#userDeleteModal").modal('hide');
        })

    </script>

@endsection


