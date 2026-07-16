@extends('Template::layouts.master')
@section('content')
    <div class="notice"></div>

    @if (!$user->work_profile_complete && $user->step < 4)
        <div class="profile-complete-notification">
            <p><i class="las la-exclamation-circle"></i> @lang('Your profile is not completed yet. Please update your information to enhance your experience.') <small>@lang('As soon as possible publish your profile!')</small> <a
                    class="update-link" href="{{ route('user.profile.skill') }}"> @lang('Completed your freelance profile') </a></p>
        </div>
    @endif

    <div class="container-fluid px-0">
        <div class="row gy-4 justify-content-center">
            @php
                $kyc = getContent('kyc.content', true);
            @endphp
            @if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
                <div class="col-md-12">
                    <div class="alert alert--danger mb-4" role="alert">
                        <span class="alert__icon">
                            <i class="fa-solid fa-circle-info"></i>
                        </span>
                        <div class="alert__content">
                            <h6 class="alert__title">@lang('KYC Verification Rejected')</h6>
                            <p class="alert__desc">{{ __(@$kyc->data_values->reject) }}<a
                                    href="{{ route('user.kyc.form') }}">@lang('Click Here to Re-submit Documents')</a> |
                                <a class="text--primary" href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($user->kv == Status::KYC_UNVERIFIED)
                <div class="col-md-12">
                    <div class="alert alert--info mb-4" role="alert">
                        <span class="alert__icon">
                            <i class="fa-solid fa-circle-info"></i>
                        </span>
                        <div class="alert__content">
                            <h6 class="alert__title">@lang('KYC Verification required')</h6>
                            <p class="alert__desc">{{ __(@$kyc->data_values->required) }} <a
                                    href="{{ route('user.kyc.form') }}">@lang('Click Here to Submit Documents')</a></p>
                        </div>
                    </div>
                </div>
            @elseif($user->kv == Status::KYC_PENDING)
                <div class="col-md-12">
                    <div class="alert alert--warning mb-4" role="alert">
                        <span class="alert__icon">
                            <i class="fa-solid fa-circle-info"></i>
                        </span>
                        <div class="alert__content">
                            <h6 class="alert__title">@lang('KYC Verification pending')</h6>
                            <p class="alert__desc">{{ __(@$kyc->data_values->pending) }} <a
                                    href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>

                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="dashboard-body-wrapper mt-4">
            <div class="dashboard-body-wrapper__content">
                <div class="row gy-4 justify-content-center">
                    <div class="col-xxl-3 col-sm-6 col-xsm-6">
                        <a class="dashboard-widget" href="{{ route('user.transactions') }}">
                            <div class="dashboard-widget__icon flex-center">
                                <i class="las la-coins"></i>
                            </div>
                            <div class="dashboard-widget__content">
                                <span class="dashboard-widget__text"> @lang('Total Earning') </span>
                                <h5 class="dashboard-widget__number"> {{ showAmount($widget['total_earning']) }}
                                </h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-sm-6 col-xsm-6">
                        <a class="dashboard-widget" href="{{ route('user.bid.index') }}">
                            <div class="dashboard-widget__icon flex-center">
                                <i class="las la-gavel"></i>
                            </div>
                            <div class="dashboard-widget__content">
                                <span class="dashboard-widget__text"> @lang('Total Job Bid') </span>
                                <h5 class="dashboard-widget__number"> {{ $widget['total_bid'] }} </h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-sm-6 col-xsm-6">
                        <a class="dashboard-widget"
                            href="{{ route('user.project.index') }}?status={{ Status::PROJECT_COMPLETED }}">
                            <div class="dashboard-widget__icon flex-center">
                                <i class="las la-briefcase"></i>
                            </div>
                            <div class="dashboard-widget__content">
                                <span class="dashboard-widget__text"> @lang('Completed Project') </span>
                                <h5 class="dashboard-widget__number"> {{ $widget['total_completed_project'] }}
                                </h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-sm-6 col-xsm-6">
                        <a class="dashboard-widget"
                            href="{{ route('user.project.index') }}?status={{ Status::PROJECT_RUNNING }}">
                            <div class="dashboard-widget__icon flex-center">
                                <i class="las la-spinner"></i>
                            </div>
                            <div class="dashboard-widget__content">
                                <span class="dashboard-widget__text"> @lang('Running Project') </span>
                                <h5 class="dashboard-widget__number"> {{ $widget['total_running_project'] }} </h5>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Dashboard Card End -->
            </div>
            <div class="dashboard-body-wrapper__sidebar">
                <div class="upgrade-profile">
                    <div class="upgrade-profile__content">
                        <h6 class="title"> @lang('Complete Your Profile')</h6>
                        <a class="btn--base btn btn--xsm" href="{{ route('user.profile.setting') }}">
                            @lang('Complete Profile') </a>
                        <a class="seeInstructionBtn d-block mt-1 text-white" data-bs-toggle="tooltip"
                            href="javascript:void(0)" title="@lang('Profile Completion Guide')"><i class="las la-book-open"></i>
                            @lang('Instruction')</a>

                    </div>

                    <div class="progress-container">
                        <div class="percent">
                            <svg class="circle" width="80" height="80">
                                <circle class="first" cx="40" cy="40" r="35"></circle>
                                <!-- Outer circle (white) -->
                                <circle class="second" style="--percent: {{ $profileCompletion }}" cx="40"
                                    cy="40" r="35"></circle> <!-- Progress circle -->
                            </svg>
                        </div>
                        <div class="percentag">
                            {{ $profileCompletion }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-content-wrapper mt-4">
            <div class="chart-box">
                <h6 class="title">@lang('Income Report') </h6>
                <div id="chart"></div>
            </div>
            <div class="dashboard-item">
                <div class="dashboard-item__top">
                    <h6 class="dashboard-item__title"> @lang('Last Delivery') </h6>
                </div>
                @forelse ($projects as $project)
                    <div class="delivery-info">
                        <div class="delivery-info__top">
                            <a class="title" href="{{ route('user.project.detail', $project->id) }}">
                                {{ strLimit($project->job->title, 35) }} </a>
                            <span class="number"> {{ showAmount($project->bid->bid_amount) }} </span>
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
                    @include('Template::partials.empty', [
                        'message' => 'Latest delivery not found!',
                    ])
                @endforelse
            </div>
        </div>
        <div class="dashboard-table mt-5">
            @include('Template::user.bid.bid_list')
            @if ($bids->count() == 5)
                <div class="dashboard-table__bottom">
                    <div class="pagination-wrapper">
                        <div class="pagination-wrapper__left"></div>
                        <div class="pagination-wrapper__right">
                            <a class="btn--base btn btn--sm" href="{{ route('user.project.index') }}"> @lang('See All Projects')
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
        <div class="modal custom--modal" id="kycRejectionReason">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                        <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $user->kyc_rejection_reason }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal custom--modal fade" id="profileInstructionModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Guide to 100% Profile Completion')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ol class="list-group">
                        <li class="list-group-item">
                            <div class="fw-bold"> @lang('1) Skills')</div>
                            <ul class="list-group list-group-flush mt-2">
                                <li class="list-group-item"> <i class="las la-check-circle"></i> @lang('If the user has 3 or more skills, they receive 20 points.')</li>
                                <li class="list-group-item"> <i class="las la-check-circle"></i> @lang('If the user has at least 1 skill but fewer than 3, they receive 10 points.')</li>
                                <li class="list-group-item"> <i class="las la-check-circle"></i> @lang('If the user has no skills, they receive 0 points.')</li>
                            </ul>
                        </li>

                        <li class="list-group-item">
                            <div class="fw-bold">@lang('2) Settings')</div>
                            <ul class="list-group list-group-flush mt-2">
                                <li class="list-group-item"> <i class="las la-check-circle"></i> @lang('10 points if image present.')</li>
                                <li class="list-group-item"> <i class="las la-check-circle"></i> @lang('5 points if bio present.')</li>
                                <li class="list-group-item"> <i class="las la-check-circle"></i> @lang('5 points if Language present.')</li>
                            </ul>
                        </li>
                        <li class="list-group-item">
                            <div class="fw-bold">@lang('3) Education')</div>
                            <ul class="list-group list-group-flush mt-2">
                                <li class="list-group-item"> <i class="las la-check-circle"></i> @lang('If the user has 2 or more educational entries, they receive 20 points.')</li>
                                <li class="list-group-item"> <i class="las la-check-circle"></i> @lang('If they have at least 1 educational entry but fewer than 2, they receive 10 points.')</li>
                                <li class="list-group-item"> <i class="las la-check-circle"></i> @lang('If they have no educational entries, they receive 0 points.')</li>
                            </ul>
                        </li>
                        <li class="list-group-item">
                            <div class="fw-bold">@lang('4) Portfolio')</div>
                            <ul class="list-group list-group-flush mt-2">
                                <li class="list-group-item"> <i class="las la-check-circle"></i> @lang('Similar to education, if the user has 2 or more portfolio items, they receive 20 points; 10 points for at least 1 item; and 0 points for none.')</li>
                            </ul>
                        </li>
                        <li class="list-group-item">
                            <div class="fw-bold">@lang('5) KYC & 2FA')</div>
                            <ul class="list-group list-group-flush mt-2">
                                <li class="list-group-item"> <i class="las la-check-circle"></i> @lang('KYC & 2FA Verified then receive 10 points for these.')</li>
                            </ul>
                        </li>
                        <li class="list-group-item">
                            <div class="fw-bold">** @lang('Profile Completion Status') **</div>
                            <ul class="list-group list-group-flush mt-2">
                                <li class="list-group-item"> @lang('50% or Below 50% is') <span
                                        class="badge badge--warning">@lang('Poor')</span></li>
                                <li class="list-group-item"> @lang('Above 50% and Below 80% is') <span
                                        class="badge badge--primary">@lang('Average')</span></li>
                                <li class="list-group-item"> @lang('Above 80%') <span
                                        class="badge badge--base">@lang('Excellent')</span></li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset(activeTemplate(true) . 'js/chart.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            var curText = "{{ gs('cur_text') }}";
            var monthlyData = @json($monthlyData);
            var primaryColor = "#{{ gs('base_color') }}";

            var options = {
                series: [{
                    name: 'Monthly Earning',
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
                        vertical: true,
                        barHeight: "50%",
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
                        },
                        hideOverlappingLabels: true,
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
                    text: '@lang('Last 12 Months Earning Report')',
                    floating: true,
                    offsetY: 330,
                    align: 'center',
                    style: {
                        color: '#000'
                    }
                },
            };
            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();


            $('.seeInstructionBtn').on('click', function() {
                var modal = $('#profileInstructionModal');
                modal.modal('show');
            });

            const $progressBar = $('.progress-container svg circle:last-of-type');
            const $progressText = $('.percentag');

            // Get the radius of the circle (in this case, 35px)
            const radius = parseFloat($progressBar.attr('r')); // Use the actual radius of the circle
            const circumference = 2 * Math.PI * radius;

            // Set the stroke-dasharray (the circumference of the circle)
            $progressBar.css('stroke-dasharray', circumference);

            // Set the initial stroke-dashoffset to start the circle from empty
            $progressBar.css('stroke-dashoffset', circumference);

            // Get the percentage value (use custom property)
            const percentage = $progressBar.css('--percent'); // Percentage from the inline style or CSS variable

            // Animate the progress bar
            $({
                countNum: 0
            }).animate({
                countNum: percentage
            }, {
                duration: 1500,
                easing: 'swing',
                step: function() {
                    // Calculate the stroke-dashoffset based on the current count
                    const progressOffset = circumference - (circumference * this.countNum / 100);
                    $progressBar.css('stroke-dashoffset', progressOffset);
                    $progressText.text(Math.floor(this.countNum) + '%');
                },
                complete: function() {
                    $progressText.text(percentage + '%');
                }
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .dashboard .dashboard-item .delivery-info__top .title {
            width: 59%;
        }

        .profile-complete-notification {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-family: Arial, sans-serif;
            align-items: center;
        }

        .profile-complete-notification small {
            color: #721c24;
        }

        .profile-complete-notification p {
            margin: 0;
        }

        .update-link {
            color: #0056b3;
            text-decoration: none;
            font-weight: bold;
            text-decoration: underline;
        }

        /* progess bar -user profiles */
        .upgrade-profile {
            background-color: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .upgrade-profile__content .title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .upgrade-profile__content .text {
            font-size: 14px;
            color: #666;
            margin: 10px 0;
        }

        .progress-container {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 20px auto 10px;
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-bg {
            stroke-dasharray: 440;
            stroke-dashoffset: 0;
            transition: stroke-dashoffset 1s linear;
        }

        .progress-bar {
            stroke-dasharray: 440;
            stroke-dashoffset: 440;
            transition: stroke-dashoffset 1.5s ease-out;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 16px;
            font-weight: bold;
            color: #14A800;
        }

        .percentag {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: hsl(var(--white));
            line-height: 1;
            font-size: 14px;
            z-index: 911;
            font-weight: 700;
        }

        .progress-container .percent {
            position: relative;
        }

        .progress-container svg {
            position: relative;
            width: 80px;
            height: 80px;
            transform: rotate(-90deg);
        }

        .progress-container svg circle {
            fill: none;
            stroke-width: 6;
        }

        /* Outer Circle (background) */
        .progress-container svg circle.first {
            stroke: #fff;
            /* White background for the remaining portion */
        }

        /* Progress Circle (the actual progress) */
        .progress-container svg circle.second {
            stroke-dasharray: 219.91;
            /* Circumference of a circle with r=35 */
            stroke-dashoffset: calc(219.91 - (219.91 * var(--percent)) / 100);
            stroke: hsl(var(--base));
            /* Progress color */
        }

        .dashboard-body-wrapper {
            display: flex;
            gap: 20px;
        }

        .upgrade-profile__content {
            text-align: left;
        }

        .dashboard-body-wrapper__sidebar {
            width: 370px;
        }

        .dashboard-body-wrapper__content {
            width: calc(100% - 370px);
        }

        @media (max-width:1499px) {
            .dashboard-body-wrapper__sidebar {
                width: 350px;
            }

            .dashboard-body-wrapper__content {
                width: calc(100% - 350px);
            }
        }

        @media (max-width:1199px) {
            .dashboard-body-wrapper {
                flex-direction: column;
            }

            .dashboard-body-wrapper__sidebar {
                width: 100%;
            }

            .dashboard-body-wrapper__content {
                width: 100%;
            }
        }

        .list-group-item {
            border: unset ! important;
        }
    </style>
@endpush
