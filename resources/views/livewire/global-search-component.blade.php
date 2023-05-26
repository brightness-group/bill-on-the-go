<div>
    <div
        class="navbar-search-wrapper search-input-wrapper  {{ isset($menuHorizontal) ? $containerNav : '' }} d-none">
        <input type="text"
               class="form-control search-input ms-4 {{ isset($menuHorizontal) ? '' : $containerNav }} border-0"
               placeholder="{{ __('locale.Search') }}..." aria-label="{{ __('locale.Search') }}..."
               data-url="{{ route('global.search') }}" style="width:33%;"/>
        <i class="bx bx-x bx-sm search-toggler cursor-pointer" style="right: 66%;"></i>
    </div>
</div>
