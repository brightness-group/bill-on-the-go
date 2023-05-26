@section('title', __('locale.Users'))

<div class="card">
{{--    <div class="card-header d-flex justify-content-between align-items-center">--}}
{{--        <h3><strong>{{ __('locale.My Users') }}</strong></h3>--}}
{{--    </div>--}}
    <div class="card-content">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-6">
                    @if(auth()->user()->hasRole('Admin'))
                        <button type="button" class="btn btn-dark" wire:click="openUserModal"><i class="bx bx-user-plus"></i>
                            <span>{{ __('locale.New') }}</span>
                        </button>
                    @endif
                </div>
                @livewire('search-field-component', ['search' => $search])
            </div>

            <!-- Form User Modal -->
            <div class="modal fade" id="formUserModal" tabindex="-1" aria-labelledby="formUserModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            @if(!$selectedItem)
                                <h5 class="modal-title" id="formUserModalLabel">{{ __('locale.Create new User') }}</h5>
                                <button type="button" class="btn-close" wire:click="closeFormModal" aria-label="{{ __('locale.Close') }}"></button>
                            @else
                                <h5 class="modal-title" id="formUserModalLabel">{{ __('locale.Edit User') }}</h5>
                                <button type="button" class="btn-close" wire:click="closeFormModal" aria-label="{{ __('locale.Close') }}"></button>
                            @endif
                        </div>
                        <div class="modal-body">
                            @livewire('tenant.user-form-component')
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <table class="table table-striped table-users">
                    <thead>
                    <tr>
                        <th class="text-justify">{{ __('locale.NAME') }}</th>
                        <th class="text-justify">{{ __('locale.EMAIL') }}</th>
                        <th>{{ __('locale.ROLE') }}</th>
                        <th>{{ __('locale.LAST LOGIN') }}</th>
                        <th>{{ __('locale.2 Factor Auth') }}</th>
                        <th>{{ __('locale.API access') }}</th>
                        <th colspan="2">{{ __('locale.ACTIONS') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if( count($users) )
                        @foreach($users as $user)
                            <!-- Delete Company's User Modal -->
                            <div class="modal fade" id="userDeleteModal-{{ $user->id }}" tabindex="-1" aria-labelledby="userDeleteModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header box-shadow-1" style="text-shadow: 1px 1px 2px #7DA0B1">
                                            <h5 class="modal-title" id="userDeleteModalLabel">{{ __('locale.Delete User') }}</h5>
                                            <button type="button" class="close" style="background-color: #c9c9c9" wire:click="closeDeleteModal" aria-label="{{ __('locale.Close') }}">
                                                <span aria-hidden="true"><strong>&times;</strong></span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @if (auth()->user()->locale == 'en')
                                                <h3>{{ __('locale.Are you sure you want to delete') }} {{  $user->name }} ?</h3>
                                            @else
                                                <h3>{{ __('locale.Are you sure you want to delete') }} {{  $user->name }} {{ __('locale.Want to delete') }} ?</h3>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" wire:click="closeDeleteModal">{{ __('locale.Cancel') }}</button>
                                            <button type="button" class="btn btn-dark" wire:click="destroy" wire:loading.attr="disabled">{{ __('locale.Yes') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                @if($user->hasRole('Admin'))
                                    <td class="text-center"><span class="badge badge-light-warning">{{ __('locale.Admin') }}</span></td>
                                @else
                                    <td class="text-center"><span class="badge badge-light-danger">{{ __('locale.User') }}</span></td>
                                @endif
                                <td class="text-center">
                                    @if($user->last_login_at)
                                        @datetime($user->last_login_at)
                                    @else
                                        <span class="badge badge-light-info">{{ __('locale.Not logged in') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->two_factor_secret)
                                        <span class="badge badge-light-success">{{ __('locale.Enabled') }}</span>
                                    @else
                                        <span class="badge badge-light-danger">{{ __('locale.Disabled') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span wire:click="toggleApiAccess({{ $user->id }})">
                                        @if($user->is_allow_api)
                                            <i class="bx bx bx-check-circle cursor-pointer" style="font-size: 20px;color: #5a8dee;"
                                               title="{{__('locale.Allowed')}}"></i>
                                        @else
                                            <i class="bx bx-x-circle cursor-pointer" style="font-size: 20px;color: gray;"
                                               title="{{__('locale.Not Allowed')}}"></i>
                                        @endif
                                    </span>
                                </td>
                                <td colspan="2" class="text-center">
                                    <div class="display-inline-block">
                                        @if(auth()->user()->hasRole('Admin'))
                                            <button wire:click="toggleLock({{ $user->id }})"
                                                    class="btn btn-icon rounded-circle btn-light-secondary"
                                                    title="{{ $user->is_allow_api ? __('locale.User Unlocked!') : __('locale.User Locked!') }}">
                                                <x-feather-icon :width="18" :height="18" :icon="$user->is_allow_api ? 'unlock' : 'lock'" />
                                            </button>
                                            <button wire:click="selectItem({{ $user->id }}, 'update')"
                                                    class="btn btn-icon rounded-circle btn-light-secondary"
                                                    title="{{ __('locale.Edit') }}">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <button wire:click="selectItem({{ $user->id }}, 'delete')"
                                                    class="btn btn-icon rounded-circle btn-light-danger"
                                                    title="{{ __('locale.Delete') }}">
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
                                <td colspan="6" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There is no users') }}</p></td>
                            @else
                                <td colspan="6" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There is no users that matches') }} "{{ $search }}"</p></td>
                            @endif
                        </tr>
                    @endif
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

@section('custom_scripts')

    <script>

        $(document).ready(function() {
            $("#formUserModal").on('hidden.bs.modal', function(){
                livewire.emit('forcedCloseUserModal');
            });

            $("[id^=userDeleteModal]").on('hidden.bs.modal', function(){
                livewire.emit('forcedCloseUserModal');
            });

            window.addEventListener('closeFormUserModal', event => {
                $("#formUserModal").modal('hide');
            });

            $(".openUserModal").on("click", () => {
                $("#formUserModal").modal("show");
            });
        });

    </script>

@endsection
