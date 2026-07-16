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
                                    <i class="las la-users-cog"></i>
                                </div>
                                <div class="widget-card-content">
                                    <h6 class="widget-card-amount">{{ $newBuyersThisMonth }}</h6>
                                    <p class="widget-card-title">@lang('New This Month')</p>
                                </div>
                            </div>
                        </div>

                        <div class="widget-card bg--success">

                            <div class="widget-card-left">
                                <div class="widget-card-icon">
                                    <i class="las la-users-cog"></i>
                                </div>
                                <div class="widget-card-content">
                                    <h6 class="widget-card-amount">{{ $newBuyersThisWeek }}</h6>
                                    <p class="widget-card-title">@lang('New This Week')</p>
                                </div>
                            </div>
                        </div>

                        <div class="widget-card bg--primary">
                            <div class="widget-card-left">
                                <div class="widget-card-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="widget-card-content">
                                    <h6 class="widget-card-amount">{{ $newJobsThisMonth }}</h6>
                                    <p class="widget-card-title">@lang('New Job This Month')</p>
                                </div>
                            </div>
                        </div>
                        <div class="widget-card bg--success">

                            <div class="widget-card-left">
                                <div class="widget-card-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="widget-card-content">
                                    <h6 class="widget-card-amount">{{ $newJobsThisWeek }}</h6>
                                    <p class="widget-card-title">@lang('New Job This Week')</p>
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
            <h5 class="py-3">@lang('Top Rated Buyers')</h5>
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
                                @foreach ($topBuyers as $buyer)
                                    <tr>
                                        <td>
                                            <div>
                                                <span class="fw-bold">{{ $buyer->fullname }}</span>
                                                <br>
                                                <span class="small">
                                                    <a
                                                        href="{{ route('admin.buyers.detail', $buyer->id) }}"><span>@</span>{{ $buyer->username }}</a>
                                                </span>
                                            </div>
                                        </td>
                                        <td>{{ $buyer->rating }} </td>
                                        <td>
                                            <ul class="review-rating-list">
                                                @php echo avgRating($buyer->rating); @endphp
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
            <h5 class="py-3">@lang('Buyer Growth Over the Year')</h5>
            <div class="card">
                <div class="card-body pt-5">
                    <div id="buyer-growth-chart"></div>
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
                data: @json($monthlyBuyerGrowth)
            }],
            xaxis: {
                categories: @json($months),
            },
            colors: ['#14A800'],
            title: {
                text: 'Buyer Growth Over the Year',
                align: 'center'
            }
        };

        var chart = new ApexCharts(document.querySelector("#buyer-growth-chart"), options);
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
            color: #fff;
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
