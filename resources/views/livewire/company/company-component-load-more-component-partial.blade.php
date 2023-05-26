<div class="page-{{ $pageNumber }}">
    @if (count($companies))
        <table class="table table-hover table-company">
            <tbody>
            @foreach($companies as $company)
                <tr>
                    <td wire:key="td-loaded-image-{{ $company->id }}-{{ time() }}"
                        @if(auth()->user()->hasRole('Admin') && !$company->status) onclick="window.location='{{ route('show.companies', ['company' => $company]) }}'"
                        style="cursor: pointer;width: 100px;" @else style="width: 100px;" @endif>
                        <img
                            src="{{ $company->logo ? url($company->logo) : asset('assets/images/backgrounds/empty.jpg') }}"
                            id="uploaded_image" width="60" alt="Logo">
                    </td>
                    <td @if(auth()->user()->hasRole('Admin') && !$company->status) onclick="window.location='{{ route('show.companies', ['company' => $company]) }}'" style="cursor: pointer;" @endif>{{ $company->name }}</td>
                    <td @if(auth()->user()->hasRole('Admin') && !$company->status) onclick="window.location='{{ route('show.companies', ['company' => $company]) }}'" style="cursor: pointer;" @endif>{{ $company->subdomain }}</td>
                    <td class="text-center" @if(auth()->user()->hasRole('Admin') && !$company->status) onclick="window.location='{{ route('show.companies', ['company' => $company]) }}'" style="cursor: pointer;" @endif>@datetime($company->created_at)</td>
                    <td class="text-center">
                        <div class="display-inline-block">
                            @if(auth('web')->user()->hasRole('Admin'))
                                @if($company->status)
                                    <button wire:click="selectItem({{ $company->id }}, 'update')" class="btn btn-sm rounded-pill btn-icon"
                                            disabled="disabled" data-toggle="modal" data-placement="top" title="">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button wire:click="loadUsersCompany({{ $company->id }})" class="btn btn-sm rounded-pill btn-icon"
                                            disabled="disabled" data-toggle="modal" data-placement="top" title="">
                                        <i class="bx bx-group"></i>
                                    </button>
                                    <button target="_blank" class="btn btn-sm rounded-pill btn-icon"
                                            disabled="disabled" data-toggle="modal" data-placement="top" title="">
                                        <i class="bx bx-link-alt"></i>
                                    </button>
                                    <button wire:click="selectItem({{ $company->id }}, 'delete')" class="btn btn-sm rounded-pill btn-icon"
                                            disabled="disabled" data-toggle="modal" data-placement="top" title="">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                    <button wire:click="statusSwitcher({{ $company->id }}, 'unlock')" class="btn btn-sm btn-icon text-danger"
                                            data-toggle="modal" data-placement="top" title="{{ __('locale.Unlock') }}">
                                        <i class="bx bx-lock"></i>
                                    </button>
                                @else
                                    <a class="btn btn-sm btn-icon text-secondary" href="{{ route('show.companies', ['company' => $company]) }}"
                                       data-placement="top" title="{{ __('locale.Edit') }}">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <button wire:click="loadUsersCompany({{ $company->id }})" class="btn btn-sm btn-icon text-info"
                                            data-placement="top" title="{{ __('locale.Users') }}">
                                        <i class="bx bx-group"></i>
                                    </button>
                                    <a  href="{{ $company->generateFullURL() }}" target="_blank" class="btn btn-sm btn-icon text-warning"
                                        data-placement="top" title="{{ __('locale.Link') }}">
                                        <i class="bx bx-link-alt"></i>
                                    </a>
                                    <button wire:click="selectItem({{ $company->id }}, 'delete')" class="btn btn-sm btn-icon text-danger"
                                            data-placement="top" title="{{ __('locale.Delete') }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                    <button wire:click="statusSwitcher({{ $company->id }}, 'lock')" class="btn btn-sm btn-icon text-dark"
                                            data-placement="top" title="{{ __('locale.Lock') }}">
                                        <i class="bx bx-lock-open"></i>
                                    </button>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    @livewire('company.company-component-load-more-component',
    [
    'pageNumber'=>$pageNumber,'bulk'=>$bulk,
    ],
    key('company-component-load-more-component-load-more'.time()))

</div>
