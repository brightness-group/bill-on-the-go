@section('title',__('locale.System Users'))

@section('custom_css')
    <link rel="stylesheet" href="{{asset('frontend/css/users-component_css.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/loading_states_awesome.css')}}">
@endsection

<div class="card">
    <div class="card-header">
        <h3><strong>{{ __('locale.System Users') }}</strong></h3>
    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-12 col-sm-6">
                    @if(auth()->user()->hasRole('Admin'))
                        <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#formUserModal">
                            <i class="bx bx-plus"></i>
                            <span>{{ __('locale.New') }}</span>
                        </button>
                    @endif
                </div>
                @livewire('search-field-component', ['search' => $search])
            </div>

            <!-- Form user modal -->
            <div class="modal fade" id="formUserModal" tabindex="-1" aria-labelledby="formUserModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="formUserModalLabel">
                                {{ !$selectedItem ? __('locale.New User') : __('locale.Edit User')}}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        @livewire('create-edit-user-form')
                    </div>
                </div>
            </div>

            <!-- Change password user modal -->
            <div class="modal fade" id="changePasswordUserModal" tabindex="-1" aria-labelledby="changePasswordUserModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordUserModalLabel">
                                @lang('locale.Change Password')
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @livewire('change-password-user-form')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete company's user modal -->
            <div class="modal fade" id="userDeleteModal" tabindex="-1" aria-labelledby="userDeleteModal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userDeleteModal">{{ __('locale.Delete User') }}</h5>
                            <button type="button" wire:click="closeUserDeleteModal" class="btn-close"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h3>{{ __('locale.Are you sure?') }}</h3>
                        </div>
                        <div class="modal-footer box-shadow-1">
                            <button type="button" class="btn btn-outline-secondary" wire:click="closeUserDeleteModal">{{ __('locale.Cancel') }}</button>
                            <button type="button" class="btn btn-dark" wire:click="destroy" wire:loading.attr="disabled">{{ __('locale.Yes') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table clickble table-hover table-users">
                    <thead>
                    <tr>
                        <th class="text-justify">{{ __('locale.NAME') }}</th>
                        <th class="text-justify">{{ __('locale.EMAIL') }}</th>
                        <th>{{ __('locale.ROLE') }}</th>
                        <th>{{ __('locale.CREATED AT') }}</th>
                        <th colspan="3">{{ __('locale.ACTIONS') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if( count($users) )
                        @foreach($users as $user)
                            <tr>
                                <td class="cursor-pointer" wire:click="selectItem({{ $user->id }}, 'update')" >{{ $user->name }}</td>
                                <td class="cursor-pointer" wire:click="selectItem({{ $user->id }}, 'update')">{{ $user->email }}</td>
                                <td class="cursor-pointer" wire:click="selectItem({{ $user->id }}, 'update')">{{ $user->hasRole('Admin') ?  __('locale.Admin') : __('locale.User')}}</td>
                                <td>@datetime($user->created_at)</td>
                                <td>
                                    <div class="display-inline-block">
                                        @if(auth('web')->user()->hasRole('Admin'))
                                            <button wire:click="selectItem({{ $user->id }}, 'update')"
                                                    class="btn btn-sm btn-icon text-secondary"
                                                    title="{{ __('locale.Edit') }}">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                        <button wire:click="selectItem({{ $user->id }}, 'change-password')"
                                                class="btn btn-sm btn-icon text-secondary"
                                                title="{{ __('locale.Change Password') }}">
                                            <i class="bx bx-lock"></i>
                                        </button>
                                            <button wire:click="selectItem({{ $user->id }}, 'delete')"
                                                    class="btn btn-sm btn-icon text-secondary"
                                                    title="{{ __('locale.Delete') }}">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            @if( $search == '')
                                <td colspan="6" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There is no users') }}</p></td>
                            @else
                                <td colspan="6" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There is no users that matches') }} "{{ $search }}"</p></td>
                            @endif
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer" style="float: right;">
            {{ $users->links() }}
        </div>
    </div>
</div>

@section('custom_scripts')

    <script>

        $(document).ready(function() {
            $("#formUserModal").on('hidden.bs.modal', function(){
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

        window.addEventListener('openFormUserModal', event => {
            $("#formUserModal").modal('show');
        })

        window.addEventListener('closeFormUserModal', event => {
            $("#formUserModal").modal('hide');
        })

        window.addEventListener('openChangePasswordUserModal', event => {
            $("#changePasswordUserModal").modal('show');
        })

        window.addEventListener('closeChangePasswordUserModal', event => {
            $("#changePasswordUserModal").modal('hide');
        })

        window.addEventListener('openUserDeleteModal', event => {
            $("#userDeleteModal").modal('show');
        })

        window.addEventListener('closeUserDeleteModal', event => {
            $("#userDeleteModal").modal('hide');
        })

    </script>

@endsection


