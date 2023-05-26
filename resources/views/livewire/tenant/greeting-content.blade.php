<div class="card">
    <div class="card-header">
        <h3 class="greeting-text">{{ __('locale.Congratulations') }} {{ auth()->user()->name }}!</h3>
        <p class="mb-0">{{ __('locale.Monthly Revenue') }}</p>
        <button
            style="top:-45px;"
            data-bs-placement="left"
            data-bs-original-title="{{ __('locale.Synchronization is in progress') }} ({{ $batchProgress }}%)"
            data-bs-custom-class="tooltip-secondary"
            class="btn btn-sm btn-outline-dark float-end position-relative batch-status {{ ($pendingJobs <= 0 ? 'd-none' : '') }}"
        >
            <i class="bx bx-repost"></i>
        </button>

        <button
            title="{{__('locale.Refresh')}}"
            style="top:-45px;"
            wire:click="computeAllWidgets"
            class="btn btn-sm btn-outline-dark float-end position-relative compute-dashboard-widgets {{ ($pendingJobs > 0 ? 'd-none' : '') }}"
        >
            <i class="bx bx-repost"></i>
        </button>
    </div>
    <div class="card-content mt-n5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-end">
                <div class="dashboard-content-left">
                    <h1 class="text-primary font-large-2 text-bold-500">{{ $monthly_revenue.'€' ?? 0 }}</h1>
                    <p>{{__('locale.You have percentage more revenue than last month in this time frame',['percentage'=> $monthly_revenue_percentage.'%'])}}</p>
                </div>
                <div class="dashboard-content-right">
                    <img src="{{ asset('assets/images/icon/cup.png') }}" height="190" width="190" class="img-fluid"
                         alt="Greetings!"/>
                </div>
            </div>
        </div>
    </div>
</div>

@section('custom_scripts')
    <script defer type="text/javascript">
        window.addEventListener('showBatchProgress', event => {
            let batchStatus = $(document).find(".batch-status");

            if (@this.pendingJobs > 0 && batchStatus.length == 1) {
                batchStatus.tooltip('show');
            }
        });

        window.addEventListener('hideBatchProgress', event => {
            let batchStatus = $(document).find(".batch-status"),
                syncBtn = $(document).find(".compute-dashboard-widgets");

            batchStatus.addClass('d-none');
            syncBtn.removeClass('d-none');
        });

        $(document).ready(function() {
            $(document).find(".batch-status").on("mouseenter", function() {
                Livewire.emit('checkRunningBatch');
            });
        });
    </script>
@endsection
