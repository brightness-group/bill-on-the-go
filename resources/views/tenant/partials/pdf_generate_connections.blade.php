<!DOCTYPE html>
<html  lang="{{ config('app.locale') }}">
<head>
{{--    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>--}}
    <title>{{ __('locale.Invoice') }}</title>
    <style>
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
        }
        h1,h2,h3,h4,h5,h6,p,span,div {
            font-size:10px;
            font-weight: normal;
        }
        th {
            font-size:10px;
        }
        td {
            font-size: 8px;
        }
        .panel {
            margin-bottom: 20px;
            background-color: #fff;
            border: 1px solid transparent;
            border-radius: 4px;
            -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
            box-shadow: 0 1px 1px rgba(0,0,0,.05);
        }
        .panel-default {
            border-color: #ddd;
        }
        .panel-body {
            padding: 15px;
        }
        table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 0px;
            border-spacing: 0;
            border-collapse: collapse;
            background-color: transparent;
        }
        thead  {
            text-align: left;
            display: table-header-group;
            vertical-align: middle;
        }
        th, td  {
            border: 1px solid #ddd;
            padding: 6px;
        }
        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
        }
        @font-face {
            font-family: 'Firefly';
            font-style: normal;
            font-weight: normal;
            src: url('../../../../storage/fonts/DejaVuSans-Bold.ufm.php') format('truetype');
        }
        .column {
            float: left;
            width: 50%;
        }
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
        .inline-elements {
            display: inline-block;

        }
        .align-vertical-center {
            padding: 6px 0;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #737373;
        }
        .client-name {
            font-size: 16px;
            font-weight: bold;
            color: black;
        }

        .charges-information-table {
            border-collapse: collapse;
            width: 60%;
        }
        .charges-information-table td, .charges-information-table th {
            border: none;
            padding: 4px;
            color: #7c7c7c;
            font-size: 11px;
            text-align: left;
        }
    </style>
</head>
<body>
<header>
    <div class="row">
        <div class="column">
            <div class="inline-elements">
                <div>
                    <span class="company-name" style="color: black;"><strong>{{ __('locale.Activity Report') }}</strong></span>
                </div>
                <div>
                    <span
                        class="client-name"><strong>{{isset($customer) ? $customer->customer_name : '' }}</strong></span>
                </div>
            </div>

        </div>
        <div class="column">
            <div class="align-vertical-center" style="margin-left: 50%;text-align: right;">
                <div>
                    <span class="company-name">{{ isset($company) ? $company->name : ''}}</span>
                </div>
                <br/>
                <div>
                    <b style="color: #737373;">{{ __('locale.Creation date') }}: <br/>
                        {{ now()->format('d.m.Y') }}
                    </b>
                </div>
                <div>
                    <b style="color: #737373;">{{ __('locale.Recording period') }}: <br/>
                        {{ isset($period) ? $period : '' }}
                    </b>
                </div>
            </div>
        </div>
    </div>
    <br />
</header>
<main>
{{--    <div style="clear:both; position:relative;">--}}
{{--        <div style="position:absolute; left: 0; width: 300pt;">--}}
{{--            <div class="panel panel-default">--}}
{{--                <div class="panel-body">--}}
{{--                    {{ __('locale.Customer') }}: {{ $customer->customer_name }}<br />--}}
{{--                    {{ __('locale.Email') }}: {{ $customer->email }}<br />--}}
{{--                    {{ __('locale.Phone number') }}: {{ $customer->phone }}<br />--}}
{{--                    {{ __('locale.Address') }}: {{ $customer->address }}<br />--}}
{{--                    {{ __('locale.ZIP') }} - {{ __('locale.City') }}: {{ $customer->post_code }} - {{ $customer->city }}<br />--}}
{{--                    {{ __('locale.Country') }}: {{ $customer->country }}<br />--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div style="margin-left: 474pt;width: 300pt;">--}}
{{--            <div class="panel panel-default">--}}
{{--                <div class="panel-body">--}}
{{--                    {{ __('locale.Company') }}: {{ $company->name }}<br />--}}
{{--                    {{ __('locale.Email') }}: {{ $company->email }}<br />--}}
{{--                    {{ __('locale.Phone number') }}: {{ $company->phone }}<br />--}}
{{--                    {{ __('locale.Address') }}: {{ $company->address }}<br />--}}
{{--                    {{ __('locale.ZIP') }} - {{ __('locale.City') }}: {{ $company->zip }} - {{ $company->city }}<br />--}}
{{--                    {{ __('locale.Country') }}: {{ $company->country }}<br />--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div style=""></div>--}}
{{--    </div>--}}
    @if(isset($charged) && count($charged))
        <table class="table table-bordered">
            <thead>
            <tr><th colspan="8" style="text-align: justify;border-top: white;border-left: white; border-right: white;"><strong>{{ __('locale.Charged Activities:') }}</strong></th></tr>
            <tr>
                <th width="15%">{{ __('locale.Device') }}</th>
                <th width="12%">{{ __('locale.User') }}</th>
                <th width="12%">{{ __('locale.Start') }}</th>
                <th width="12%">{{ __('locale.End') }}</th>
                <th width="7%">{{ __('locale.Duration') }}</th>
                <th width="7%">{{ __('locale.Units') }}</th>
                <th width="7%">{{ __('locale.Tariff') }}</th>
                <th width="7%">{{ __('locale.Price') }}</th>
                <th>{{ __('locale.Job Description') }}</th>
            </tr>
            </thead>
            <tbody>
            @php
                $sumOfChargedUnits = 0;
            @endphp
                @foreach ($charged as $item)
                    @php
                    $units = $item->calculateUnit();
                    $sumOfChargedUnits += $units;
                    @endphp
                    <tr>
                        <td>{{ $item->devicename }}</td>
                        <td>{{ $item->username }}</td>
                        <td style="text-align: center;"><strong>{{ strtoupper(__('locale.'.substr($item->charsForStartDate(),0,2))) }}</strong> - @datetime($item->start_date)</td>
                        <td style="text-align: center;"><strong>{{ strtoupper(__('locale.'.substr($item->charsForEndDate(),0,2))) }}</strong> - @datetime($item->end_date)</td>
                        <td style="text-align: center;">{{ $item->duration() }}m</td>
                        <td style="text-align: center;">{{ $units ?? '-' }}</td>
                        <td style="text-align: center;">{{ !empty($item->tariff->tariff_name)? $item->tariff->tariff_name : '-' }}</td>
                        <td style="text-align: center;">{{ $item->price ? $item->price.' €' : '-' }}</td>
                        <td>{{ $item->notes }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="border-left: white;border-right: white;border-bottom: white;"></td>
                    <td style="text-align: center;border-top: 2px solid black;border-left: white;border-right: white;border-bottom: white;font-size: 13px;">{{ $sumChargedDuration }}m</td>
                    <td style="text-align: center;border-left: white;border-right: white;border-bottom: white;font-size: 13px;">{{ $sumOfChargedUnits }}</td>
                    <td style="text-align: center;border-left: white;border-right: white;border-bottom: white;"></td>
                    <td style="text-align: center;border-top: 2px solid black;border-left: white;border-right: white;border-bottom: white;font-size: 13px;">{{ $sumCharged.' €' }}</td>
                    <td style="border-right: white;border-left: white;border-bottom: white;"></td>
                </tr>
            </tbody>
        </table>
    @endif

    @if(isset($notCharged) && count($notCharged))
        <table class="table table-bordered">
            <thead>
            <tr><th colspan="8" style="text-align: justify;border-top: white;border-left: white; border-right: white;"><strong>{{ __('locale.Uncharged Activities:') }}</strong></th></tr>
            <tr>
                <th width="15%">{{ __('locale.Device') }}</th>
                <th width="12%">{{ __('locale.User') }}</th>
                <th width="12%">{{ __('locale.Start') }}</th>
                <th width="12%">{{ __('locale.End') }}</th>
                <th width="7%">{{ __('locale.Duration') }}</th>
                <th width="7%">{{ __('locale.Units') }}</th>
                <th width="7%">{{ __('locale.Tariff') }}</th>
                <th width="7%">{{ __('locale.Price') }}</th>
                <th>{{ __('locale.Job Description') }}</th>
            </tr>
            </thead>
            <tbody>
            @php
                $sumOfUnchargedUnits = 0;
            @endphp
            @foreach($notCharged as $item)
                @php
                    $units = $item->calculateUnit();
                    $sumOfUnchargedUnits += $units;
                @endphp
                <tr>
                    <td>{{ $item->devicename }}</td>
                    <td>{{ $item->username }}</td>
                    <td style="text-align: center;"><strong>{{ strtoupper(__('locale.'.substr($item->charsForStartDate(),0,2))) }}</strong> - @datetime($item->start_date)</td>
                    <td style="text-align: center;"><strong>{{ strtoupper(__('locale.'.substr($item->charsForEndDate(),0,2))) }}</strong> - @datetime($item->end_date)</td>
                    <td style="text-align: center;">{{ $item->duration() }}m</td>
                    <td style="text-align: center;">{{ $units ?? '-' }}</td>
                    <td style="text-align: center;">{{ !empty($item->tariff->tariff_name)? $item->tariff->tariff_name : '-' }}</td>
                    <td style="text-align: center;">---</td>
                    <td>{{ $item->notes }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" style="border-left: white;border-right: white;border-bottom: white;"></td>
                <td style="text-align: center;border-top: 2px solid black;border-left: white;border-right: white;border-bottom: white;font-size: 13px;">{{ $sumNotChargedDuration }}m</td>
                <td style="text-align: center;border-left: white;border-right: white;border-bottom: white;font-size: 13px;">{{ $sumOfUnchargedUnits ?? '-'}}</td>
                <td style="text-align: center;border-left: white;border-right: white;border-bottom: white;"></td>
                <td style="text-align: center;border-top: 2px solid black;border-left: white;border-right: white;border-bottom: white;">---</td>
                <td style="border-right: white;border-left: white;border-bottom: white;"></td>
            </tr>
            </tbody>
        </table>
    @endif

    {{-- tariff charges information for user --}}
    @if(isset($charged) && count($charged))
        <table class="table charges-information-table">
        <thead>
        <tr>
            <th class="text-justify">{{ __('locale.Name') }}</th>
            <th>{{ __('locale.Days') }}</th>
            <th>{{ __('locale.Time') }}</th>
            <th class="text-center">{{ __('locale.Interval') }}</th>
            <th>{{ __('locale.Price per Interval') }}</th>
            <th>{{ __('locale.Units consumed') }}</th>
            <th>{{ __('locale.Total price per tariff') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($appliedTariffs as $tariff)
            <tr>
                <td>
                    {{ !empty($tariff->tariff_name) ? $tariff->tariff_name : 'N/A' }} &nbsp; &nbsp;
                </td>
                <td>
                    @php
                        $selected_days = \App\Helpers\Helper::getFormattedDays($tariff->selected_days);
                    @endphp
                    @foreach($selected_days as $key => $dayString)
                        <span>{{$dayString}}</span>
                    @endforeach
                </td>
                <td>
                    {{ ($tariff->initial_time) }} - {{ $tariff->end_time }}
                </td>
                <td>{{ $tariff->interval }}</td>
                <td>
                    {{ !empty($tariff->getRawOriginal('price')) ? $tariff->getRawOriginal('price').' €' : '-' }}
                </td>
                <td>
                    {{ !empty($tariff->total_unit) ? $tariff->total_unit : 0 }}
                </td>
                <td>
                    {{ !empty($tariff->total_price) ? $tariff->total_price.' €' : 0 }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</main>
</body>
</html>
