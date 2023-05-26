@section('title', __('locale.Border Line Connection'))

@section('custom_css')
    <link rel="stylesheet" href="{{asset('/frontend/css/connection_border_line_component_css.css')}}">
@endsection

<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-auto mr-auto">
                                <h4>{{ __('locale.Border Line Emergence') }}</h4>
                            </div>
                            <div class="col-auto ml-auto">
                                <a href="{{ route('customers.show',['customer' => $group]) }}" class="btn btn-icon pr-0" title="{{ __('locale.Edit Customer') }}">
                                    <i class="bx bx-edit" style="font-size: 25px;color: #0544d3;"></i>
                                </a>
                                <a href="{{ route('customer.connections') }}" class="btn btn-icon pl-0" title="{{ __('locale.Connections') }}">
                                    <i class="bx bx-list-ul" style="font-size: 25px;color: #0544d3;"></i>
                                </a>
                            </div>
                        </div>
                        <hr class="box-shadow-6">
                        <div class="row">
                            <div class="col">
                                <a class="badge badge-primary"><span style="color: #c8f5de">{{ __('locale.Connection') }}</span></a>
                                <table class="table table-striped table-border-line-connection">
                                    <thead>
                                    <tr>
                                        @foreach($headers as $key => $value)
                                            <th class="text-center">
                                                <span>{{ __('locale.'.$value) }}</span>
                                            </th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ $connection->username }}</td>
                                        <td>{{ $connection->groupname }}</td>
                                        <td>@datetime($connection->start_date->setTimezone(config('site.default_timezone')))</td>
                                        <td>@datetime($connection->end_date->setTimezone(config('site.default_timezone')))</td>
                                        <td>{{ $connection->duration() }}m</td>
                                        <td>{{ $connection->calculateUnit() }}</td>
                                        <td>{{ $tariff_connection->tariff_name }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <a class="badge badge-primary"><span style="color: #c8f5de">{{ __('locale.Crossed Tariff') }}</span></a>
                                <table class="table table-striped table-border-line-connection">
                                    <thead>
                                    <tr>
                                        @foreach($headers_tariff as $key => $value)
                                            @if($key !== 'interval')
                                                <th class="text-center">
                                                    <span>{{ __('locale.'.$value) }}</span>
                                                </th>
                                            @else
                                                <th class="text-justify pr-0">
                                                    IT <small>({{ __('locale.min') }})</small>
                                                </th>
                                            @endif
                                        @endforeach
                                        <th class="p-0"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr> {{--style="@if($tariff_related->id == $selectedTariff) background-color: #c8f5de @endif;"--}}
                                        <td>{{ $tariff_related->tariff_name }}</td>
                                        <td>{{ $tariff_related->initial_time }}</td>
                                        <td>{{ $tariff_related->end_time }}</td>
                                        <td>{{ $tariff_related->interval }}m</td>
                                        <td class="p-0">
                                            <i class="bx bx-show-alt" style="color: #0544d3;"></i>
                                        </td>
                                    </tr>
                                    <tr> {{--style="@if($tariff_overlaps->id == $selectedTariff) background-color: #c8f5de @endif;"--}}
                                        <td>{{ $tariff_overlaps->tariff_name }}</td>
                                        <td>{{ $tariff_overlaps->initial_time }}</td>
                                        <td>{{ $tariff_overlaps->end_time }}</td>
                                        <td>{{ $tariff_overlaps->interval }}m</td>
                                        <td class="p-0">
                                            <i class="bx bx-show-alt" style="color: #0544d3;"></i>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col">
                                <a class="badge badge-primary"><span style="color: #c8f5de">{{ __('locale.Info') }}</span></a>
                                <table class="table table-striped table-diff-in-connection">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-center">{{ __('locale.before related tariff') }}</th>
                                        <th class="text-center">{{ __('locale.after crossed tariff') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ strtoupper(__('locale.minutes')) }}</td>
                                        <td class="text-center">{{ $minutesBefore }}</td>
                                        <td class="text-center">{{ $minutesAfter }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ strtoupper(__('locale.Units')) }}</td>
                                        <td class="text-center">{{ $unitsBefore }}</td>
                                        <td class="text-center">{{ $unitsAfter }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8"></div>
                            <div class="col">
                                <label for="select_tariff" style="font-family: Rubik,Helvetica, Arial, sans-serif;font-size: 10px;color: #475F7B;text-transform: uppercase;letter-spacing: 1px;">
                                    <strong>{{ __('locale.Apply Tariff') }}</strong></label>
                                <select id="select_tariff" class="form-control" wire:model="selectedTariff">
                                    @foreach($selecTariffs as $tariff)
                                        <option value="{{ $tariff->id }}">{{ $tariff->tariff_name }}</option>
                                    @endforeach
                                </select>
                                <fieldset class="pt-1">
                                    <div class="checkbox">
                                        <input type="checkbox" class="checkbox-input" id="resolve_checkbox" wire:model="resolver">
                                        <label for="resolve_checkbox">{{ __('locale.Resolve conflict') }}</label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row justify-content-end align-content-end pt-2">
                            <div class="d-flex flex-sm-row flex-column justify-content-end mr-1">
                                <button {{ !$resolver ? 'disabled' : '' }} class="btn btn-primary" wire:click="save">{{ __('locale.Save') }}</button>
                                <button class="btn btn-secondary" style="margin-left: 5px;" wire:click="cancel">{{ __('locale.Cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--@section('custom_scripts')--}}
{{--    <script>--}}
{{--        $(document).ready(function(){--}}
{{--            toastr.options = {--}}
{{--                positionClass: 'toast-top-center',--}}
{{--                showDuration: 1000,--}}
{{--                timeOut: 3000,--}}
{{--                hideDuration: 2000,--}}
{{--                showMethod: 'fadeIn',--}}
{{--                hideMethod: 'fadeOut'--}}
{{--            }--}}

{{--            window.addEventListener('showToastrSuccess', event => {--}}
{{--                toastr.success('', event.detail.message).css("width","fit-content")--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endsection--}}
