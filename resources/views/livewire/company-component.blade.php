@section('title',__('locale.Companies'))

@section('custom_css')
    <link rel="stylesheet" href="{{asset('frontend/css/system/company-component_css.css') }}">
    <link rel="stylesheet" href="{{asset('frontend/css/loading_states_awesome.css')}}">
    <style>
        .image_area {
            position: relative;
        }
        img#uploaded_image {
            display: block;
            max-width: 100%;
        }
    </style>
@endsection

@push('header-script')
    <script type="text/javascript">
        function listingData() {
            return {
                selectedCompany: "#showModal-{{ request()->get('id', '') }}",
                showCompanyEle: [],
                init() {
                    // Open company for global search.
                    this.showCompanyEle = this.$el.querySelectorAll(this.selectedCompany);

                    // Show modal of selected company.
                    this.showCompanyModal();
                },
                showCompanyModal() {
                    if (this.showCompanyEle.length > 0) {
                        this.showCompanyEle[0].click();

                        // Remove query string of "id" after open modal.
                        const url = new URL(window.location);
                        url.searchParams.set('id', '');
                        window.history.pushState(null, '', url.toString());
                    }
                }
            };
        };
    </script>
@endpush

<div>
    <div class="card">
        <div class="card-header">
            <h3><strong>{{ __('locale.List Companies') }}</strong></h3>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-12 col-md-6">
                        <div class="row g-2">
                            <div class="col-md-2">
                                <select class="form-select" id="bulk" wire:model="bulk">
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="300">300</option>
                                    <option value="100000000">{{ __('locale.All') }}</option>
                                </select>
                                <input id="isLoadMoreHiddenInput" type="hidden"  value="{{ $isLoadMore }}" wire:key="isLoadMoreHiddenInput-{{ $isLoadMore }}">
                                <input id="hasMorePageHiddenInput" type="hidden"  value="{{ $hasMorePage }}" wire:key="hasMorePageHiddenInput-{{ $hasMorePage }}">
                            </div>
                            @if(auth('web')->user()->hasRole('Admin'))
                                <div class="col-md-6">
                                    <a class="btn btn-outline-dark" href="javascript:void(0);" wire:click="$emit('showModal', 'show-company-component')">
                                        <i class="bx bx-plus"></i>
                                        <span>{{ __('locale.New') }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @livewire('search-field-component', ['search' => $search])
                </div>

                {{-- Delete Company Modal --}}
                <div class="modal fade" id="modalFormDelete" tabindex="-1" aria-labelledby="deleteCompanyModal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteCompanyModal">{{ __('locale.Delete Company') }}</h5>
                                <button type="button" class="btn-close" wire:click="closeDeleteModal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h3>{{ __('locale.Are you sure?') }}</h3>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" wire:click="closeDeleteModal">{{ __('locale.Cancel') }}</button>
                                <button type="button" class="btn btn-dark" wire:click="destroy">{{ __('locale.Yes') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Companies --}}
                <div x-data="listingData()" wire:key="{{ rand() }}">
                    <div class="table-responsive text-nowrap">
                        <table class="table clickble table-hover table-company">
                            <thead>
                                <tr>
                                    <th>{{ __('locale.Logo') }}</th>
                                    <th >{{ __('locale.Name') }}</th>
                                    <th>{{ __('locale.Subdomain') }}</th>
                                    <th class="text-center">{{ __('locale.Suscripted at') }}</th>
                                    <th class="text-center">{{ __('locale.ACTIONS') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($companies))
                                @foreach($companies as $company)
                                    <tr>
                                        <td wire:key="td-loaded-image-{{ $company->id }}-{{ time() }}"
                                            @if(auth()->user()->hasRole('Admin') && !$company->status) wire:click="$emit('showModal', 'show-company-component', {{ $company }})"
                                            style="cursor: pointer;width: 100px;" @else style="width: 100px;" @endif>
                                            <img
                                                src="{{ $company->logo ? url($company->logo) : asset('assets/images/backgrounds/empty.jpg') }}"
                                                id="uploaded_image" width="60" alt="Logo">
                                        </td>
                                        <td id="showModal-{{ $company->id }}" @if(auth()->user()->hasRole('Admin') && !$company->status) wire:click="$emit('showModal', 'show-company-component', {{ $company }})" style="cursor: pointer;" @endif>{{ $company->name }}</td>
                                        <td @if(auth()->user()->hasRole('Admin') && !$company->status) wire:click="$emit('showModal', 'show-company-component', {{ $company }})" style="cursor: pointer;" @endif>{{ $company->subdomain }}</td>
                                        <td class="text-center" @if(auth()->user()->hasRole('Admin') && !$company->status) wire:click="$emit('showModal', 'show-company-component', {{ $company }})" style="cursor: pointer;" @endif>@datetime($company->created_at)</td>
                                        <td class="text-center">
                                            <div class="display-inline-block">
                                                @if(auth('web')->user()->hasRole('Admin'))
                                                    @if($company->status)
                                                        <button class="btn btn-sm btn-icon text-secondary" disabled="disabled" data-toggle="modal" data-placement="top" title="">
                                                            <i class="bx bx-edit"></i>
                                                        </button>
                                                        <button target="_blank" class="btn btn-sm btn-icon text-secondary" disabled="disabled" data-toggle="modal" data-placement="top" title="">
                                                            <i class="bx bx-link-alt"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-icon text-secondary" disabled="disabled" data-toggle="modal" data-placement="top" title="">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                        <button wire:click="statusSwitcher({{ $company->id }}, 'unlock')" class="btn btn-sm btn-icon text-secondary" data-toggle="modal" data-placement="top" title="{{ __('locale.Unlock') }}">
                                                            <i class="bx bx-lock"></i>
                                                        </button>
                                                    @else
                                                        <a class="btn btn-sm btn-icon text-secondary" href="javascript:void(0);" data-placement="top" title="{{ __('locale.Edit') }}" wire:click="$emit('showModal', 'show-company-component', {{ $company }})">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <a  href="{{ $company->generateFullURL() }}" target="_blank" class="btn btn-sm btn-icon text-secondary" data-placement="top" title="{{ __('locale.Link') }}">
                                                            <i class="bx bx-link-alt"></i>
                                                        </a>
                                                        <button wire:click="selectItem({{ $company->id }}, 'delete')" class="btn btn-sm btn-icon text-secondary" data-placement="top" title="{{ __('locale.Delete') }}">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                        <button wire:click="statusSwitcher({{ $company->id }}, 'lock')" class="btn btn-sm btn-icon text-secondary" data-placement="top" title="{{ __('locale.Lock') }}">
                                                            <i class="bx bx-lock-open"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    @if($search == '')
                                        <td colspan="5" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There is no companies') }}</p></td>
                                    @else
                                        <td colspan="5" class="text-center"><p class="alert alert-dismissible">{{ __('locale.There is no results match with') }} "{{$search}}"</p></td>
                                    @endif
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer" style="float: right;">
                <div>
                    @if (count($companies) && (int)$bulk <= 1000)
                        {{ $companies->links() }}
                    @elseif ((int)$bulk > 1000 && $hasMorePage)
                        <tr>
                            <td colspan="12">
                                <div id="div-load-more-spinner" class="d-flex justify-content-center align-items-center invisible">
                                    <span style="color: #475F7B; font-size: 10px;font-weight: bold;">{{ strtoupper( __('locale.Loading More')) }}</span>
                                    <div style="margin-left: 5px;align-self: center;">
                                        <div class="la-line-scale la-dark la-sm" style="width: 30px;">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('custom_scripts')

    <script>

        $(document).ready(function() {
            Livewire.emit('showToastrMessageForCompanyComponent');
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
            window.addEventListener('showToastrInfo', event => {
                toastr.info(event.detail.text,event.detail.message).css("width","fit-content")
            })
            window.addEventListener('showToastrDelete', event => {
                toastr.warning('',event.detail.message).css("width","fit-content")
            })
        });

        let count = 0;
        let findEle = null;
        let isInputCheckEvent = false;
        window.onscroll = function (ev) {
            if ((window.innerHeight + window.scrollY) >= $(document).height() - 1000) {
                let isLoadMore = $('#isLoadMoreHiddenInput').val();
                let hasMorePage = $('#hasMorePageHiddenInput').val();
                if (count === 0 && isLoadMore && hasMorePage && (findEle == null || findEle.length == 0)) {
                    if ($(document).find('#load_more_company_btn').length == 1) {
                        $('#loading-text-spinner').text('{{ __('locale.Loading More') }}');
                        $('#div-load-more-spinner').removeClass('invisible');
                        $(document).find('#load_more_company_btn').trigger('click');
                        if($(document).find('.page-2').length == 0 && isInputCheckEvent){
                            $('#div-load-more-spinner').removeClass('invisible');
                            window.livewire.emitTo('company.company-component-load-more-component','loadMoreUpdatePage');
                        }
                    }
                    count++;
                }
            }
        };

        findEle = $(document).find('.company-component');
        window.addEventListener('loadMoreConnectionSuccess', event => {
            $('#div-load-more-spinner').addClass('invisible');
            count = 0;
        });


        //for company's users modal
        window.addEventListener('openUserCompanyModal', event => {
            $("#userCompanyModal").modal('show');
        })

        window.addEventListener('closeUserCompanyModal', event => {
            $("#userCompanyModal").modal('hide');
        })

        window.addEventListener('openUserCompanyDeleteModal', event => {
            $("#userCompanyDeleteModal").modal('show');
        })

        window.addEventListener('closeUserCompanyDeleteModal', event => {
            $("#userCompanyDeleteModal").modal('hide');
        })

        //for company modals
        window.addEventListener('closeFormModal', event => {
            $("#formCompanyModal").modal('hide');
        })

        window.addEventListener('openFormModal', event => {
            $("#formCompanyModal").modal('show');
        })

        window.addEventListener('openDeleteModal', event => {
            $("#modalFormDelete").find('.modal-body').find('h3').html(event.detail.deleteMsg);

            $("#modalFormDelete").modal('show');
        })

        window.addEventListener('closeDeleteModal', event => {
            $("#modalFormDelete").modal('hide');
        })

        window.addEventListener('openDeleteLogoModal', event => {
            $("#modalFormDeleteLogo").modal('show');
        });

        window.addEventListener('closeDeleteLogoModal', event => {
            $("#modalFormDeleteLogo").modal('hide');
        });
    </script>

@endsection
