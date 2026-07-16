@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-3">
        <div class="col-md-6">
            <h5 class="py-3">@lang('Top Freelancer Earnings')</h5>
            <div class="card card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Total Earnings')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($freelancerEarnings as $user)
                                <tr>
                                    <td>
                                        <div>
                                            <span class="fw-bold">{{ $user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a
                                                    href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                            </span>
                                        </div>
                                    </td>
                                    <td>{{ showAmount($user->total_earning) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h5 class="py-3">@lang('Top Buyer Payments')</h5>
            <div class="card card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Total Paid')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buyerPayments as $buyer)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $buyer->fullname }}</span>
                                        <br>
                                        <span class="small">
                                            <a
                                                href="{{ route('admin.buyers.detail', $buyer->id) }}"><span>@</span>{{ $buyer->username }}</a>
                                        </span>
                                    </td>
                                    <td>{{ showAmount($buyer->total_paid) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="row gy-4 my-4">
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="fas fa-users overlay-icon text--info"></i>
                <div class="widget-two__icon b-radius--5 bg--info">
                    <i class="fas fa-users"></i>
                </div>
                <div class="widget-two__content">
                    <h3>{{ showAmount($revenueMetrics['todayRevenue']) }}</h3>
                    <p>@lang("Today's Project Completed")</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="fas fa-ticket-alt overlay-icon text--primary"></i>
                <div class="widget-two__icon b-radius--5 bg--primary">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="widget-two__content">
                    <h3>{{ showAmount($revenueMetrics['last7DaysRevenue']) }}</h3>
                    <p>@lang('Last 7 Days Project Completed')</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="fas fa-film overlay-icon text--1"></i>
                <div class="widget-two__icon b-radius--5 bg--1">
                    <i class="fas fa-film"></i>
                </div>
                <div class="widget-two__content">
                    <h3>{{ showAmount($revenueMetrics['last30DaysRevenue']) }}</h3>
                    <p>@lang('Last 30 Days Project Completed')</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="fas fa-hand-holding-usd overlay-icon text--success"></i>
                <div class="widget-two__icon b-radius--5 bg--success">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="widget-two__content">
                    <h3>{{ showAmount($revenueMetrics['thisYearRevenue']) }}</h3>
                    <p>@lang('This Year Project Completed')</p>
                </div>
            </div>
        </div>        
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <h3 id="amountDisplay" class="mb-0">
                                @lang('Total Amount'): {{ showAmount($revenueMetrics['totalRevenue']) }}
                            </h3>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex flex-column align-items-end">
                                <h5 class="mb-2">@lang('Filter By Date')</h5>
                                <input name="date" type="search" id="revenueDatePicker"
                                    class="datepicker-here form-control bg--white pe-2 date-range w-100"
                                    placeholder="@lang('Start Date - End Date')" autocomplete="off" value="{{ request()->date }}">
                            </div>
                        </div>
                    </div>

                    <div id="chart"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush





@push('script')
    <script>
        $(document).ready(function() {
            "use strict";


            const datePicker = $('.date-range').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                showDropdowns: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf(
                        'month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                },
                maxDate: moment()
            });


            const changeDatePickerText = (event, startDate, endDate) => {
                $(event.target).val(startDate.format('MMMM DD, YYYY') + ' - ' + endDate.format(
                    'MMMM DD, YYYY'));
            };


            $('.date-range').on('apply.daterangepicker', (event, picker) => {
                changeDatePickerText(event, picker.startDate, picker.endDate);
                fetchFinancialData(picker.startDate, picker.endDate);
            });


            $('.date-range').on('cancel.daterangepicker', () => {
                $('.date-range').val('');
                fetchFinancialData(null, null);
            });


            let chart;
            const curText = "{{ gs('cur_text') }}";
            const initialStartDate = moment().subtract(11, 'months').startOf('month');
            const initialEndDate = moment().endOf('month');

            fetchFinancialData(initialStartDate, initialEndDate);


            function fetchFinancialData(startDate, endDate) {
                const url = "{{ route('admin.report.financial.analytics.date.filter') }}";
                const data = {
                    date: startDate && endDate ? startDate.format('YYYY-MM-DD') + ' - ' + endDate.format(
                        'YYYY-MM-DD') : '',
                };

                $.get(url, data, function(response) {
                    if (response.status === 'success') {
                        updateChart(response.data);
                    } else {
                        console.error('Failed to fetch data:', response);
                    }
                });
            }

            // Function to update the chart
            function updateChart(monthlyData) {
                const options = {
                    series: [{
                        name: 'Project Completed',
                        data: monthlyData.map(item => item.total_bid),
                    }],
                    chart: {
                        height: 350,
                        type: 'bar',
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 10,
                            dataLabels: {
                                position: 'top',
                            },
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: val => val + ' ' + curText,
                        offsetY: -20,
                        style: {
                            fontSize: '12px',
                            colors: ["#14A800"]
                        }
                    },
                    xaxis: {
                        categories: monthlyData.map(item => item.date),
                        position: 'top',
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        crosshairs: {
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    colorFrom: '#14A800',
                                    colorTo: '#14A800',
                                    stops: [0, 100],
                                    opacityFrom: 0.4,
                                    opacityTo: 0.5,
                                }
                            }
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    yaxis: {
                        labels: {
                            show: true,
                            formatter: val => val + ' ' + curText,
                        }
                    },
                    title: {
                        text: 'Freelancer Earning Report',
                        floating: true,
                        offsetY: 330,
                        align: 'center',
                        style: {
                            color: '#000'
                        }
                    }
                };

                // Destroy existing chart (if any)
                if (chart) {
                    chart.destroy();
                }

                // Render new chart
                chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            }
        });
    </script>
@endpush



@push('style')
    <style>
        .apexcharts-menu {
            min-width: 120px !important;
        }

        #chart {
            border-top: 1px solid #e3e6f0;
            padding-top: 1.5rem;
        }

        .card {
            border-radius: 0.5rem;
        }
    </style>
@endpush
