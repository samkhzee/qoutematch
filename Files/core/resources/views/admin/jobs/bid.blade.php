@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">

            <div class="show-filter mb-3 text-end">
                <button type="button" class="btn btn-outline--primary showFilterBtn btn-sm"><i class="las la-filter"></i>
                    @lang('Filter')</button>
            </div>
            <div class="card responsive-filter-card mb-4">
                <div class="card-body">
                    <form>
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label>@lang('Username')</label>
                                <input type="search" name="search" value="{{ request()->search }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control form--control select2"
                                    data-minimum-results-for-search="-1">
                                    <option value="">@lang('All')</option>
                                    <option value="0" @selected(request()->status != null && request()->status == Status::BID_PENDING)>@lang('Pending')</option>
                                    <option value="1" @selected(request()->status == Status::BID_ACCEPTED)>@lang('Hired')</option>
                                    <option value="3" @selected(request()->status == Status::BID_REJECTED)>@lang('Rejected')</option>
                                    <option value="4" @selected(request()->status == Status::BID_WITHDRAW)>@lang('Withdrawn')</option>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="search"
                                    class="datepicker-here form-control bg--white pe-2 date-range"
                                    placeholder="@lang('Start Date - End Date')" autocomplete="off" value="{{ request()->date }}">
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i>
                                    @lang('Filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th> @lang('Freelancer')</th>
                                    <th> @lang('Buyer')</th>
                                    <th> @lang('Estimate Time') </th>
                                    <th> @lang('Budget')</th>
                                    <th> @lang('Bidden At')</th>
                                    <th> @lang('Status')</th>
                                    <th> @lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bids as $bid)
                                    <tr>
                                        <td>{{ $bid->user->fullname }}
                                            <span class="small d-block">
                                                <a href="{{ route('admin.users.detail', $bid->user_id) }}"
                                                    target="__blank"><span>{{ @$bid->user->username }}</span></a>
                                            </span>
                                        </td>
                                        <td>
                                            {{ $bid->buyer->fullname }}
                                            <span class="small d-block">
                                                <a href="{{ route('admin.buyers.detail', $bid->buyer_id) }}"
                                                    target="__blank"><small> {{ $bid->buyer->username }} |</small></a>
                                                <a href="{{ route('freelance.jobs', ['buyer' => $bid->buyer->username]) }}"
                                                    target="__blank"><small> @lang('All Jobs')</small></a>
                                            </span>
                                        </td>
                                        <td>{{ strLimit(__($bid->estimated_time), 30) }}</td>
                                        <td>{{ showAmount($bid->bid_amount) }}<br>
                                            @if ($bid->job->custom_budget)
                                                <span class="badge badge--warning"> @lang('Customized')
                                                @else
                                                    <span class="badge badge--primary"> @lang('Fixed')
                                            @endif
                                            </span>
                                        </td>
                                        <td>{{ showDateTime($bid->created_at, 'd M, Y') }}</td>
                                        <td> @php echo $bid->statusBadge @endphp</td>
                                        <td>
                                            <a href="{{ route('admin.bids.detail', $bid->id) }}"
                                                class="btn btn-sm btn-outline--primary">
                                                <i class="las la-desktop"></i> @lang('View')
                                            </a>
                                            @if ((int) $bid->status !== Status::BID_ACCEPTED)
                                                <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.bids.delete', $bid->id) }}"
                                                    data-question="@lang('Are you sure to permanently delete this quote?')">
                                                    <i class="las la-trash"></i> @lang('Delete')
                                                </button>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($bids->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($bids) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"

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
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                        .endOf('month')
                    ],
                    'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                },
                maxDate: moment()
            });
            const changeDatePickerText = (event, startDate, endDate) => {
                $(event.target).val(startDate.format('MMMM DD, YYYY') + ' - ' + endDate.format('MMMM DD, YYYY'));
            }


            $('.date-range').on('apply.daterangepicker', (event, picker) => changeDatePickerText(event, picker
                .startDate, picker.endDate));

            if ($('.date-range').val()) {
                let dateRange = $('.date-range').val().split(' - ');
                $('.date-range').data('daterangepicker').setStartDate(new Date(dateRange[0]));
                $('.date-range').data('daterangepicker').setEndDate(new Date(dateRange[1]));
            }

        })(jQuery)
    </script>
@endpush
