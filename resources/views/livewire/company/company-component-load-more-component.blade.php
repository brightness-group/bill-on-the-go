<div>
    @if (!empty($company) && count($company))
        <button wire:click="$emitSelf('loadMoreForCompanyList')" class="btn btn-primary d-none"
            id="load_more_company_btn">
            Load More Company
        </button>
    @endif
</div>
