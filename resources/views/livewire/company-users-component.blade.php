<div>
    <div class="row">
        <div class="col-12 col-sm-6">
            @if(auth('web')->user()->hasRole('Admin'))
                <button type="button" class="btn btn-outline-dark" wire:click="openUserModal">
                    <i class="bx bx-plus"></i>
                    <span>{{ __('locale.New') }}</span>
                </button>
            @endif
        </div>
        @livewire('search-field-component', ['search' => $term, 'listener' => self::SEARCH_LISTENER])
    </div>

    <br />

    {{-- Company users --}}
    <div>
        <table class="table clickble table-hover table-company-users">
            <thead>
            <tr>
                <th class="text-justify" scope="col">{{ __('locale.Name') }}</th>
                <th class="text-justify" scope="col">{{ __('locale.Email') }}</th>
                <th class="text-center">{{ __('locale.Role') }}</th>
                <th scope="col">{{ __('locale.CREATED AT') }}</th>
                <th class="text-center">{{ __('locale.2 Factor Auth') }}</th>
                <th scope="col" colspan="2" class="text-center">{{ __('locale.ACTIONS') }}</th>
            </tr>
            </thead>
            <tbody>
            @if( count($modelsUsers) )
                @foreach($modelsUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            {{ $user->hasRole('Admin') ? __('locale.Admin') : __('locale.User')}}
                        </td>
                        <td class="text-center">{{ ($user->created_at)->format('H:i d.m.Y') }}</td>
                        <td class="text-center">
                            {{ $user->two_factor_secret ? __('locale.Enabled') : __('locale.Disabled') }}
                        </td>
                        <td class="text-center">
                            @if(auth('web')->user()->hasRole('Admin'))
                                <div class="display-inline-block">
                                    <button wire:click="selectItem({{ $user->id }}, 'update')" class="btn btn-icon rounded-circle btn-light-secondary" data-toggle="tooltip" data-placement="top" title="{{ __('locale.Edit') }}">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button wire:click="selectItem({{ $user->id }}, 'delete')" class="btn btn-icon rounded-circle btn-light-danger" data-toggle="tooltip" data-placement="top" title="{{ __('locale.Delete') }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    @if( $term == '')
                        <td colspan="6" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There is no users') }}</p></td>
                    @else
                        <td colspan="6" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There is no users that matches') }} "{{ $term }}"</p></td>
                    @endif
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="pt-3" style="float: right;">
        {{ $modelsUsers->links() }}
    </div>
</div>
