@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="card custom--card mb-4">
                <div class="card-body">
                    <div class="widget-card-inner">
                        <div class="widget-card bg--primary">
                            <div class="widget-card-left">
                                <div class="widget-card-icon">
                                    <i class="las la-users"></i>
                                </div>
                                <div class="widget-card-content">
                                    <h6 class="widget-card-amount">{{ $newFreelancersThisMonth }}</h6>
                                    <p class="widget-card-title">@lang('New This Month')</p>
                                </div>
                            </div>
                        </div>

                        <div class="widget-card bg--success">
                            <div class="widget-card-left">
                                <div class="widget-card-icon">
                                    <i class="las la-users"></i>
                                </div>
                                <div class="widget-card-content">
                                    <h6 class="widget-card-amount">{{ $newFreelancersThisWeek }}</h6>
                                    <p class="widget-card-title">@lang('New This Week')</p>
                                </div>
                            </div>
                        </div>

                        <div class="widget-card bg--primary">
                            <div class="widget-card-left">
                                <div class="widget-card-icon">
                                    <i class="las la-project-diagram"></i>
                                </div>
                                <div class="widget-card-content">
                                    <h6 class="widget-card-amount">{{ $newProjectThisMonth }}</h6>
                                    <p class="widget-card-title">@lang('New Project This Month')</p>
                                </div>
                            </div>
                        </div>
                        <div class="widget-card bg--success">
                            <div class="widget-card-left">
                                <div class="widget-card-icon">
                                    <i class="las la-project-diagram"></i>
                                </div>
                                <div class="widget-card-content">
                                    <h6 class="widget-card-amount">{{ $newProjectThisWeek }}</h6>
                                    <p class="widget-card-title">@lang('New Project This Week')</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h5 class="py-3">@lang('Top Rated Freelancers')</h5>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Rating')</th>
                                    <th>@lang('Star')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topFreelancers as $user)
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
                                        <td>{{ $user->rating }} </td>
                                        <td>
                                            <ul class="review-rating-list">
                                                @php echo avgRating($user->rating); @endphp
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <h5 class="py-3">@lang('Freelancer Growth Over the Year')</h5>
            <div class="card">
                <div class="card-body pt-5">
                    <div id="freelancer-growth-chart"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- ApexCharts Script -->
@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
@endpush

@push('script')
    <script>
        var options = {
            chart: {
                type: 'line',
                height: 350
            },
            series: [{
                name: 'Freelancers',
                data: @json($monthlyFreelancerGrowth)
            }],
            xaxis: {
                categories: @json($months),
            },
            colors: ['#14A800'],
            title: {
                text: 'Freelancer Growth Over the Year',
                align: 'center'
            }
        };

        var chart = new ApexCharts(document.querySelector("#freelancer-growth-chart"), options);
        chart.render();
    </script>
@endpush


@push('style')
    <style>
         @media (max-width: 1024px) {
            .apexcharts-toolbar {
            margin-top: 1.5rem;
            }
        }

        .review-rating-list {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            gap: 5px;
            margin: 12px 0;
        }

        .review-rating-list__item {
            background-color: hsl(var(--success));
            color: hsl(var(--white));
            font-size: 16px;
            width: 22px;
            height: 22px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .rating-list__item {
            color: #FDCC0D
        }
    </style>
@endpush
