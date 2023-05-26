<div>
    <div class="search-input-icon"><i class="bx bx-search primary"></i></div>
    <input type="text" id="input-for-search" placeholder="{{ __('locale.Select a Company') }}..." tabindex="-1" data-search="template-search" wire:model="search">
    <div class="search-input-close"><i class="bx bx-x" wire:click="closeSearchPanel"></i></div>
    <ul class="search-list" id="searchCompanyList">
        @if(count($companies))
            @foreach($companies as $item)
                <li class="auto-suggestion d-flex align-items-center justify-content-between cursor-pointer" id="searchCompanyItem" wire:key="{{ $item->id }}">
                    <a class="d-flex align-items-center justify-content-between w-100" onclick="window.location='{{ route('show.companies', ['company' => $item]) }}'">
                        <div class="d-flex justify-content-start">
                            <span>{{ $item->name }}</span>
                        </div>
                    </a>
                </li>
            @endforeach
        @else
            <li class="auto-suggestion d-flex align-items-center justify-content-between cursor-pointer">
                <a class="d-flex align-items-center justify-content-between w-100" href="">
                    <div class="d-flex justify-content-start">
                        <span class="mr-75 bx bx-error-circle"></span>
                        <span>{{ __('locale.No results found') }}</span>
                    </div>
                </a>
            </li>
        @endif
    </ul>
</div>

