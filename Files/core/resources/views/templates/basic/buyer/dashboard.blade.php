@extends('Template::layouts.buyer_master')
@section('content')
    <div class="notice"></div>

    <div class="row gy-4 justify-content-center">

        @php
            $kyc = getContent('kyc.content', true);
            $buyer = auth()->guard('buyer')->user();
        @endphp

        @if ($buyer->kv == Status::KYC_UNVERIFIED && $buyer->kyc_rejection_reason)
            <div class="col-md-12">
                <div class="alert alert--danger" role="alert">
                    <span class="alert__icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </span>
                    <div class="alert__content">
                        <h6 class="alert__title">@lang('KYC Documents Rejected')</h6>
                        <p class="alert__desc">{{ __(@$kyc->data_values->reject) }}<a
                                href="{{ route('buyer.kyc.form') }}">@lang('Click Here to Re-submit Documents')</a> |
                            <a class="text--primary" href="{{ route('buyer.kyc.data') }}">@lang('See KYC Data')</a>
                        </p>
                    </div>
                </div>
            </div>
        @elseif($buyer->kv == Status::KYC_UNVERIFIED)
            <div class="col-md-12">
                <div class="alert alert--info" role="alert">
                    <span class="alert__icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </span>
                    <div class="alert__content">
                        <h6 class="alert__title">@lang('KYC Verification required')</h6>
                        <p class="alert__desc">{{ __(@$kyc->data_values->required) }}<a
                                href="{{ route('buyer.kyc.form') }}">@lang('Click Here to Submit Documents')</a></p>
                    </div>
                </div>
            </div>
        @elseif($buyer->kv == Status::KYC_PENDING)
            <div class="col-md-12">
                <div class="alert alert--warning" role="alert">
                    <span class="alert__icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </span>
                    <div class="alert__content">
                        <h6 class="alert__title">@lang('KYC Verification pending')</h6>
                        <p class="alert__desc">{{ __(@$kyc->data_values->pending) }} <a
                                href="{{ route('buyer.kyc.data') }}">@lang('See KYC Data')</a></p>

                    </div>
                </div>
            </div>
        @endif

        <div class="col-xl-12">
            <!-- Dashboard Card Start -->
            <div class="row gy-4 justify-content-center">
                <div class="col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('buyer.project.index') }}" class="dashboard-widget">
                        <div class="dashboard-widget__icon flex-center">
                            <i class="las la-coins"></i>
                        </div>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text"> @lang('Total Project') </span>
                            <h5 class="dashboard-widget__number"> {{ $widget['total_project'] }} </h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('buyer.job.post.index') }}" class="dashboard-widget">
                        <div class="dashboard-widget__icon flex-center">
                            <i class="las la-gavel"></i>
                        </div>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text"> @lang('Total Job Bid') </span>
                            <h5 class="dashboard-widget__number"> {{ $widget['total_bid'] }} </h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('buyer.project.index') }}?status={{ Status::PROJECT_RUNNING }}"
                        class="dashboard-widget">
                        <div class="dashboard-widget__icon flex-center">
                            <i class="las la-spinner"></i>
                        </div>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text"> @lang('Running Project') </span>
                            <h5 class="dashboard-widget__number"> {{ $widget['total_running_project'] }} </h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('buyer.project.index') }}?status={{ Status::PROJECT_BUYER_REVIEW }}"
                        class="dashboard-widget">
                        <div class="dashboard-widget__icon flex-center">
                            <i class="las la-spinner"></i>
                        </div>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text"> @lang('Reviewing Project') </span>
                            <h5 class="dashboard-widget__number"> {{ $widget['total_reviewing_project'] }} </h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('buyer.job.post.index') }}?status={{ Status::JOB_COMPLETED }}"
                        class="dashboard-widget">
                        <div class="dashboard-widget__icon flex-center">
                            <i class="las la-briefcase"></i>
                        </div>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text"> @lang('Completed Job') </span>
                            <h5 class="dashboard-widget__number"> {{ $widget['total_job_completed'] }} </h5>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('buyer.transactions') }}" class="dashboard-widget">
                        <div class="dashboard-widget__icon flex-center">
                            <i class="las la-briefcase"></i>
                        </div>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text"> @lang('Hold Amount') </span>
                            <h5 class="dashboard-widget__number"> {{ showAmount($holdBalance) }} </h5>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Dashboard Card End -->
        </div>
        <div class="col-lg-12">
            <div class="dashboard-content-wrapper">
                <div class="chart-box">
                    <h6 class="title">@lang('Expense Report') </h6>
                    <div id="chart"></div>
                </div>
                <div class="dashboard-item">
                    <div class="dashboard-item__top">
                        <h6 class="dashboard-item__title"> @lang('Last Delivery') </h6>
                    </div>
                    @forelse ($projects as $project)
                        <div class="delivery-info">
                            <div class="delivery-info__top">
                                <a href="{{ route('user.project.detail', $project->id) }}" class="title">
                                    {{ strLimit($project->job->title, 32) }} </a>
                                <span class="number"> {{ showAmount($project?->bid?->bid_amount) }} </span>
                            </div>
                            <div class="delivery-info__content">
                                <div>
                                    <span class="title"> @lang('Buyer') </span>
                                    <p class="text"> {{ $project->buyer->fullname }} </p>
                                </div>
                                <div>
                                    <span class="title"> @lang('Location') </span>
                                    <p class="text"> {{ $project->buyer->country_name }} </p>
                                </div>
                                <div>
                                    <span class="title"> @lang('Delivery Date') </span>
                                    <p class="text"> {{ showDateTime($project->uploaded_at, 'd M, Y') }} </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        @include('Template::partials.empty', ['message' => 'Latest delivery not found!'])
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <div class="table-wrapper mt-5">
        <div class="table-wrapper-header">
            <h5 class="mb-0">@lang('Latest Jobs')</h5>
        </div>
        <div class="dashboard-table">
            @include('Template::buyer.job.job_list')

            @if ($jobs->count() == 5)
                <div class="dashboard-table__bottom">
                    <div class="pagination-wrapper">
                        <div class="pagination-wrapper__left"></div>
                        <div class="pagination-wrapper__right">
                            <a href="{{ route('buyer.job.post.index') }}" class="btn--base btn btn--sm">
                                @lang('See All Jobs')
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($buyer->kv == Status::KYC_UNVERIFIED && $buyer->kyc_rejection_reason)
        <div class="modal custom--modal fade" id="kycRejectionReason">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $buyer->kyc_rejection_reason }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection


@push('script-lib')
    <script src="{{ asset(activeTemplate(true) . 'js/chart.js') }}"></script>
@endpush

@push('script')
    <script>
        var curText = "{{ gs('cur_text') }}";
        var monthlyData = @json($monthlyData);
        var primaryColor = "#{{ gs('base_color') }}";

        var options = {
            series: [{
                name: 'Monthly Expenses',
                data: monthlyData.map(function(item) {
                    return item.total_bid;
                }),
            }],
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    borderRadius: 1,
                    dataLabels: {
                        position: 'top',
                    },
                    horizontal: false,
                    barHeight: "100%"
                }
            },
            dataLabels: {
                enabled: false,
                formatter: function(val) {
                    return val > 0 ? val + ' ' + curText : 0;
                },
                offsetY: -10,
                style: {
                    fontSize: '10px',
                    colors: ["#000"]
                },
                textAnchor: 'middle',
                position: 'bottom'
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val) {
                        return val + ' ' + curText;
                    }
                }
            },
            xaxis: {
                categories: monthlyData.map(function(item) {
                    var parts = item.month.split(' ');
                    var month = parts[0];
                    var year = parts[1] ? parts[1].slice(-2) : '';
                    return month + ' ' + year;
                }),
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '10px'
                    }
                },
                formatter: function(value, index) {
                    return monthlyData[index].total_bid > 0 ? value : 0;
                },
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
                            colorFrom: primaryColor,
                            colorTo: primaryColor,
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: false,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: true,
                    formatter: function(val) {
                        return val + ' ' + curText;
                    }
                }
            },
            colors: [primaryColor],
            title: {
                text: 'Last 12 Months Expense Report',
                floating: true,
                offsetY: 330,
                align: 'center',
                style: {
                    color: '#000'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endpush


@push('style')
    <style>
        .dashboard .dashboard-item .delivery-info__top .title {
            width: 59%;
        }
    </style>
@endpush
