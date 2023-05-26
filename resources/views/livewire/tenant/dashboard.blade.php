@section('title', __('locale.Dashboard'))

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/dashboard.css') }}">
@endsection

<section id="dashboard-ecommerce">
    <div class="row">
        <!-- Greetings Content Starts -->
        <div class="col-xl-12 col-md-12 col-12 mb-4 dashboard-greetings">
            @livewire('tenant.greeting-content',$greeting_widget)
        </div>
    </div>

    <div class="row">
        <!-- Conversion Chart Starts-->
        <div class="col-xl-12 col-lg-12 col-12">
            @livewire('tenant.turn-over-chart',['turnover_widget'=>$turnover_widget])
        </div>
    </div>

    <br />

    <div class="row">
        <!-- Overview operating times & Sales History Starts -->
        <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
                <div class="card-body py-0 px-1">
                    <div class="row">
                        <div class="row not-margin-left col-md-6 col-sm-12" style="padding: 0;margin: 0;">
                            <!-- Overview operating times Starts -->
                            @livewire('tenant.overview-operating-times-chart', ['operating_times_widget' => $operating_times_widget])
                        </div>

                        <div class="col-md-6 col-sm-12" style="padding: 0;margin: 0;">
                            <!-- Top Customers Starts -->
                            @livewire('tenant.top-customers', ['topCustomers' => $topCustomers])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br />

    <div class="row">
        <!-- Statistics Multi Radial Chart Starts -->
        <div class="col-lg-6 mb-3">
            @livewire('tenant.statistics-multi-redial-chart',['statistic_widget' => $statistic_widget])
        </div>

        <!-- Revenue Category Card Starts-->
        <div class="col-lg-6 mb-3 activity-card">
            @livewire('tenant.revenue-category-card',['revenue_category_widget' => $revenue_category_widget])
        </div>
    </div>
</section>

@section('vendor-script')
    <script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
    <script src="{{ mix('assets/js/ui-popover.js') }}"></script>
@endsection

@section('page-script')
    <script>
        if (isDarkStyle) {
            axisColor = config.colors_dark.axisColor;
        } else {
            axisColor = config.colors.axisColor;
        }

        // Stacked Bar Negative Chart
        // --------------------------
        window.addEventListener('renderTurnoverChart', event => {
            var seriesData = @this.turnover_widget.monthly_data;

            if (event.detail) {
                seriesData = event.detail;
            }

            initConversionChart(seriesData);
        });

        // Statistics Multi Radial
        // -----------------------
        window.addEventListener('renderStatisticsRadialChart', event => {
            var series = event && event.detail ? event.detail : null;

            initStatisticsRadialChart(series);
        });

        // Overview Operating Times Chart
        // ------------------------------

        window.addEventListener('renderOverviewOperatingTimesChart', event => {
            var seriesData = @this.operating_times_widget;

            if (event.detail) {
                seriesData = event.detail;
            }

            initOverviewOperatingTimesChart(seriesData);
        });

        // Statistics Multi Radial
        // -----------------------
        function initStatisticsRadialChart(series) {
            $(document).find("#no-data").addClass("d-none");

            if (!series || series == "" || Object.values(series.series_data).length <= 0) {
                $(document).find("#no-data").removeClass("d-none");

                return false;
            }

            var statisticsRadialChartOptions = {
                chart: {
                    height: 390,
                    type: "radialBar",
                },
                colors: ['#00cfdd','#ff5b5c','#5a8dee','#39da8a','#fdac41'],
                series: series.series_data,
                labels: series.customers_name,
                legend: {
                    show: true,
                    floating: true,
                    fontSize: '14px',
                    position: 'left',
                    offsetX: -10,
                    offsetY: -10,
                    inverseOrder: true,
                    labels: {
                        useSeriesColors: false,
                        colors: axisColor,
                    },
                    markers: {
                        size: 0
                    },
                    formatter: function(seriesName, opts) {
                        let legend = '';

                        legend = '<span>';
                            legend += seriesName + '&nbsp;:&nbsp;';
                        legend += '</span>';

                        legend += '<span>';
                            legend += series['total_price'][opts.seriesIndex] + '&euro;';
                        legend += '</span>';

                        return legend;
                    },
                    itemMargin: {
                        vertical: 3
                    }
                },
                plotOptions: {
                    radialBar: {
                        offsetY: 0,
                        startAngle: 0,
                        endAngle: 270,
                        hollow: {
                            margin: 3,
                            size: '40%',
                            image: undefined,
                        },
                        track: {
                            background: isDarkStyle ? '#36445d' : '#f4f4f4',
                        },
                        dataLabels: {
                            name: {
                                show: true,
                                color: axisColor,
                                offsetY: -10,
                                formatter: function (val) {
                                    return val;
                                },
                            },
                            value: {
                                show: true,
                                color: axisColor,
                                offsetY: -2,
                            },
                            total: {
                                show: true,
                                color: axisColor,
                                label: "{{__("locale.Total")}}",
                                formatter: function (val) {
                                    return '100%';
                                }
                            }
                        },
                    }
                },
            }

            document.getElementById("statistics-multi-radial-chart").innerHTML = "";

            const chartElement = document.createElement('div');

            chartElement.setAttribute('id', 'statistics-multi-radial-chart-inner');

            document.getElementById("statistics-multi-radial-chart").appendChild(chartElement);

            var statisticsRadialChart = new ApexCharts(
                document.querySelector("#statistics-multi-radial-chart-inner"),
                statisticsRadialChartOptions
            );

            statisticsRadialChart.render();
        }

        // Stacked Bar Negative Chart
        // --------------------------
        function initConversionChart(seriesData) {
            var barNegativeChartOptions = {
                chart: {
                    height: 250,
                    stacked: true,
                    type: 'bar',
                    toolbar: {
                        show: false
                    },
                    sparkline: {
                        enabled: true,
                    },
                },
                plotOptions: {
                    bar: {
                        columnWidth: '20%',
                        endingShape: 'rounded',
                    },
                    distributed: true,
                },
                colors: ['#5A8DEE', '#FDAC41'],
                series: [{
                    name: "{{ __('locale.Without Interval') }}",
                    data: seriesData.without_interval_inputs
                }, {
                    name: "{{ __('locale.Interval') }}",
                    data: seriesData.interval_time
                }],
                xaxis: {
                    offsetX: 11,
                    offsetY: -5,
                    categories: seriesData.months_inputs,
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    labels: {
                        show: true,
                        style: {
                            colors: axisColor
                        },
                        rotate: -45,
                        rotateAlways: true,
                        trim: true,
                        minHeight: 40
                    }
                },
                grid: {
                    show: true,
                },
                legend: {
                    labels: {
                        colors: axisColor
                    },
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    intersect: false,
                    shared: true,
                    custom: function({series, seriesIndex, dataPointIndex, w}) {
                        let tooltip = '';

                        let getPostfix = function(value) {
                            let hours   = Math.floor(value / 60),
                                minutes = (value % 60),
                                postfix = hours + ":" + minutes + "h";

                            return postfix;
                        };

                        tooltip = '<div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">';
                            if (seriesData.duration === 'monthly') {
                                tooltip += '{{__('locale.Month')}}: ';
                            } else {
                                tooltip += '{{__('locale.Period')}}: ';
                            }

                            tooltip += w.globals.labels[dataPointIndex];
                        tooltip += '</div>';

                        // For without interval.
                        tooltip += '<div class="apexcharts-tooltip-series-group apexcharts-active" style="order: 1; display: flex;">';
                            tooltip += '<span class="apexcharts-tooltip-marker" style="background-color: #5A8DEE;"></span>';
                            tooltip += '<div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">';
                                tooltip += '<div class="apexcharts-tooltip-y-group">';
                                    tooltip += '<span class="apexcharts-tooltip-text-y-label">' + "{{ __('locale.Without Interval') }} : " + '</span>';
                                    tooltip += '<span class="apexcharts-tooltip-text-y-value">';
                                        tooltip += '<div class="d-flex justify-content-between" style="padding-left: 5px;">';
                                            tooltip += '<div class="mw-50">';
                                                tooltip += seriesData['without_interval_price_sum'][dataPointIndex] + '&euro; ';
                                            tooltip += '</div>';

                                            tooltip += '<div class="mw-50">';
                                                tooltip += getPostfix(seriesData['without_interval_inputs'][dataPointIndex]);
                                            tooltip += '</div>';

                                            tooltip += '<div class="mw-50">';
                                                tooltip += seriesData['without_interval_percentage'][dataPointIndex] + '%';
                                            tooltip += '</div>';
                                        tooltip += '</div>';
                                    tooltip += '</span>';
                                tooltip += '</div>';

                                tooltip += '<div class="apexcharts-tooltip-goals-group">';
                                    tooltip += '<span class="apexcharts-tooltip-text-goals-label"></span>';
                                    tooltip += '<span class="apexcharts-tooltip-text-goals-value"></span>';
                                tooltip += '</div>';

                                tooltip += '<div class="apexcharts-tooltip-z-group">';
                                    tooltip += '<span class="apexcharts-tooltip-text-z-label"></span>';
                                    tooltip += '<span class="apexcharts-tooltip-text-z-value"></span>';
                                tooltip += '</div>';
                            tooltip += '</div>';
                        tooltip += '</div>';

                        // For interval.
                        tooltip += '<div class="apexcharts-tooltip-series-group apexcharts-active" style="order: 1; display: flex;">';
                            tooltip += '<span class="apexcharts-tooltip-marker" style="background-color: #FDAC41;"></span>';
                            tooltip += '<div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">';
                                tooltip += '<div class="apexcharts-tooltip-y-group">';
                                    tooltip += '<span class="apexcharts-tooltip-text-y-label">' + "{{ __('locale.With Interval') }} : " + '</span>';
                                    tooltip += '<span class="apexcharts-tooltip-text-y-value">';
                                        tooltip += '<div class="d-flex justify-content-between" style="padding-left: 5px;">';
                                            tooltip += '<div class="mw-50">';
                                                tooltip += seriesData['interval_price'][dataPointIndex] + '&euro; ';
                                            tooltip += '</div>';

                                            tooltip += '<div class="mw-50">';
                                                tooltip += getPostfix(seriesData['interval_time'][dataPointIndex]);
                                            tooltip += '</div>';

                                            tooltip += '<div class="mw-50">';
                                                tooltip += seriesData['interval_percentage'][dataPointIndex] + '%';
                                            tooltip += '</div>';
                                        tooltip += '</div>';
                                    tooltip += '</span>';
                                tooltip += '</div>';

                                tooltip += '<div class="apexcharts-tooltip-goals-group">';
                                    tooltip += '<span class="apexcharts-tooltip-text-goals-label"></span>';
                                    tooltip += '<span class="apexcharts-tooltip-text-goals-value"></span>';
                                tooltip += '</div>';

                                tooltip += '<div class="apexcharts-tooltip-z-group">';
                                    tooltip += '<span class="apexcharts-tooltip-text-z-label"></span>';
                                    tooltip += '<span class="apexcharts-tooltip-text-z-value"></span>';
                                tooltip += '</div>';
                            tooltip += '</div>';
                        tooltip += '</div>';

                        // For total.
                        tooltip += '<div class="apexcharts-tooltip-series-group apexcharts-active" style="order: 1; display: flex;">';
                            tooltip += '<span class="apexcharts-tooltip-marker" style="background-color: #808080;"></span>';
                            tooltip += '<div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">';
                                tooltip += '<div class="apexcharts-tooltip-y-group">';
                                    tooltip += '<span class="apexcharts-tooltip-text-y-label">' + "{{ __('locale.Total') }} : " + '</span>';
                                    tooltip += '<span class="apexcharts-tooltip-text-y-value">';
                                        tooltip += '<div class="d-flex justify-content-between" style="padding-left: 5px;">';
                                            tooltip += '<div class="mw-50">';
                                                tooltip += seriesData['with_interval_price_sum'][dataPointIndex] + '&euro; ';
                                            tooltip += '</div>';

                                            tooltip += '<div class="mw-50">';
                                                tooltip += getPostfix(seriesData['with_interval_inputs'][dataPointIndex]);
                                            tooltip += '</div>';

                                            tooltip += '<div class="mw-50">';
                                                tooltip += seriesData['with_interval_percentage'][dataPointIndex] + '%';
                                            tooltip += '</div>';
                                        tooltip += '</div>';
                                    tooltip += '</span>';
                                tooltip += '</div>';

                                tooltip += '<div class="apexcharts-tooltip-goals-group">';
                                    tooltip += '<span class="apexcharts-tooltip-text-goals-label"></span>';
                                    tooltip += '<span class="apexcharts-tooltip-text-goals-value"></span>';
                                tooltip += '</div>';

                                tooltip += '<div class="apexcharts-tooltip-z-group">';
                                    tooltip += '<span class="apexcharts-tooltip-text-z-label"></span>';
                                    tooltip += '<span class="apexcharts-tooltip-text-z-value"></span>';
                                tooltip += '</div>';
                            tooltip += '</div>';
                        tooltip += '</div>';

                        return tooltip;
                    }
                },
            };

            document.getElementById("bar-negative-chart").innerHTML = "";

            const barChartElement = document.createElement('div');

            barChartElement.setAttribute('id', 'bar-negative-chart-inner');

            document.getElementById("bar-negative-chart").appendChild(barChartElement);

            var barNegativeChart = new ApexCharts(
                document.querySelector("#bar-negative-chart-inner"),
                barNegativeChartOptions
            );

            barNegativeChart.render();
        }

        // Overview Operating Times Chart
        // ------------------------------
        function initOverviewOperatingTimesChart(seriesData) {
            var overviewOperatingTimesChartOptions = {
                chart: {
                    height: 250,
                    type: 'line',
                    stacked: false,
                    toolbar: {
                        show: true,
                    },
                    sparkline: {
                        enabled: false
                    },
                },
                zoom: {
                    enabled: true,
                    type: 'x',
                    autoScaleYaxis: false
                },
                colors: ['#5A8DEE', '#FDAC41'],
                dataLabels: {
                    enabled: false
                },
                /*fill: {
                    type: 'gradient',
                    gradient: {
                        inverseColors: false,
                        shade: 'light',
                        type: "vertical",
                        gradientToColors: ['#E2ECFF', '#5A8DEE'],
                        opacityFrom: 0.7,
                        opacityTo: 0.55,
                        stops: [0, 80, 100]
                    }
                },*/
                legend: {
                    labels: {
                        colors: axisColor
                    },
                },
                series: [{
                    name: "{{ __('locale.Actual Operating Time') }}",
                    data: seriesData.actual_operating_time_data,
                    type: 'line',
                }, {
                    name: "{{ __('locale.Planned Operating Time') }}",
                    data: seriesData.planned_operating_time_data,
                    type: 'line',
                }],
                stroke: {
                    curve: 'smooth',
                    width: 2.5,
                    dashArray: [0, 8]
                },
                grid: {
                    padding: {
                        left: 30
                    }
                },
                xaxis: {
                    categories: seriesData.duration_time_data,
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    labels: {
                        show: true,
                        style: {
                            colors: axisColor
                        },
                        rotate: -45,
                        rotateAlways: true,
                        trim: true,
                        minHeight: 40
                    }
                },
                yaxis: {
                    show: true,
                    showAlways: true,
                    decimalsInFloat: 2,
                    labels: {
                        show: true,
                        style: {
                            colors: axisColor
                        }
                    }
                },
                tooltip: {
                    x: {
                        formatter: function (value, {series, seriesIndex, dataPointIndex, w}) {
                            if (seriesData.duration_time_data[dataPointIndex] == 0) {
                                return 0;
                            }
                            return (seriesData.duration === 'monthly')
                                ? '{{__('locale.Month')}}: ' + seriesData.duration_time_data[dataPointIndex]
                                : '{{__('locale.Period')}}: ' + seriesData.duration_time_data[dataPointIndex];
                        }
                    },
                    y: {
                        formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
                            let times = String(value).split(".");
                            let hours = (times[0]) ? times[0] : '00';
                            let minutes = (times[1]) ? times[1] : '00';

                            // Pad zero for single character.
                            if (hours.length == 1) {
                                hours = '0' + hours;
                            }
                            if (minutes.length == 1) {
                                minutes = minutes + '0';
                            }

                            // Show two chars for minutes.
                            if (minutes.length > 2) {
                                minutes = minutes.substring(0, 2);
                            }

                            return hours + ":" + minutes + "h";
                        }
                    }
                },
            }

            document.getElementById("overview-operating-times-chart").innerHTML = "";

            const chartElement = document.createElement('div');

            chartElement.setAttribute('id', 'overview-operating-times-chart-inner');

            document.getElementById("overview-operating-times-chart").appendChild(chartElement);

            var overviewOperatingTimesChart = new ApexCharts(
                document.querySelector("#overview-operating-times-chart-inner"),
                overviewOperatingTimesChartOptions
            );

            overviewOperatingTimesChart.render();
        }

        $(document).ready(function () {
            initStatisticsRadialChart(@this.statistic_widget);
            initConversionChart(@this.turn_over_duration_months == 12 ? @this.turnover_widget.quarterly_data : @this.turnover_widget.monthly_data);
            initOverviewOperatingTimesChart(@this.initial_operating_times_widget);
        });

        window.addEventListener('showToastrSuccess', event => {
            unblockUI();
            toastr.success('', event.detail.message).css("width", "fit-content");
        });

        $('.compute-dashboard-widgets').click( event => {
            blockUI();
        });

        // Unblock UI
        // ------------------------------
        function unblockUI(){
            $.unblockUI;
        }

        // Block UI
        // ------------------------------
        function blockUI(){
            $.blockUI({
                message: '<span class="spinner-border" style="position:relative;top:8px;right: 4px;" role="status" aria-hidden="true"></span> {{__('locale.Please wait...')}}',
                //timeout: 2000, //unblock after 2 seconds
                overlayCSS: {
                    backgroundColor: '#1a233a',
                    opacity: 0.8,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    color: '#fff',
                    backgroundColor: 'transparent',
                }
            });
        }
    </script>
@endsection
