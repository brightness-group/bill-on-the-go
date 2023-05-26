<div class="card">
    <div class="card-header pb-xl-0 pt-xl-1">
        <div class="conversion-title d-flex justify-content-between pt-3">
            <div>
                <h5>
                    {{ __('locale.Turnover through Teambilling') }}
                </h5>
            </div>

            <div class="h5 ms-1">
                @if (isset($turnoverWidgetData['turnover_average']))
                    <span style="color: #FDAC41;">
                        {{ \App\Helpers\Helper::formatHoursAndMinutes($turnoverWidgetData['turnover_average'],'%02d:%02dh') }}
                    </span>
                    <span>
                        {{-- @if ($turnoverWidgetData['turnover_average'] > 0)
                            <i class="bx bx-trending-up text-success font-size-large align-middle mr-25"
                            title="{{ __('locale.Up') }}"></i>
                        @elseif ($turnoverWidgetData['turnover_average'] == 0)
                            <i class="bx bx-minus text-warning font-size-large align-middle mr-25"
                            title="{{ __('locale.Avg') }}"></i>
                        @elseif ($turnoverWidgetData['turnover_average'] < 0)
                            <i class="bx bx-trending-down text-danger font-size-large align-middle mr-25"
                            title="{{ __('locale.Down') }}"></i>
                        @endif --}}
                    </span>
                @endif

                &nbsp;&nbsp;

                <span class="conversion-rate pt-3" style="color: #FDAC41;">
                    {{ !empty($turnoverWidgetData['more_turnover']) ? number_format($turnoverWidgetData['more_turnover']).'€' : '0€' }}

                    &nbsp;

                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#infoModel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                        </svg>
                    </a>
                </span>
            </div>

            <div>
                <ul class="nav nav-pills custom-dashboard-tabs ml-10" role="tablist" style="float: right;">
                    <li class="nav-item text-center">
                        <button type="button"
                                class="btn btn-sm {{ $durationMonths == 1 ? 'btn-dark' : 'btn-outline-dark' }} me-1"
                                wire:click="renderTurnoverChart(1)">{{ __('locale.Month') }}</button>
                    </li>
    
                    <li class="nav-item text-center">
                        <button type="button"
                                class="btn btn-sm {{ $durationMonths == 12 ? 'btn-dark' : 'btn-outline-dark' }}"
                                wire:click="renderTurnoverChart(12)">{{ __('locale.Quarter') }}</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-content mb-4">
        <div class="card-body text-center">
            <div id="bar-negative-chart"></div>
        </div>
    </div>

    <div class="modal fade" id="infoModel" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalToggleLabel">{{ __('locale.Infobox') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! __('locale.turnover chart info') !!}
                </div>
            </div>
        </div>
    </div>
</div>
