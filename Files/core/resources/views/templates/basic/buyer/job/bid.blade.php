@extends('Template::layouts.buyer_master')
@section('content')
    <div class="table-wrapper">
        <div class="table-wrapper-header gap-3">
            <div class="show-filter mb-3 text-end">
                <button type="button" class="btn btn--base showFilterBtn btn--sm"><i class="las la-filter"></i>
                    @lang('Filter')</button>
            </div>
            <div class="responsive-filter-card my-4">
                <form>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="flex-grow-1">
                            <input type="search" name="search" value="{{ request()->search }}"
                                placeholder="@lang('Search by Job')" autocomplete="off" class="form-control form--control">
                        </div>
                        <div class="flex-grow-1">
                            <select name="status" class="form-control form--control select2 "
                                data-minimum-results-for-search="-1">
                                <option value="">@lang('All Status')</option>
                                <option value="0" @selected(request()->status != null && request()->status == Status::BID_PENDING)>@lang('Pending')</option>
                                <option value="1" @selected(request()->status == Status::BID_ACCEPTED)>@lang('Accepted')</option>
                                <option value="3" @selected(request()->status == Status::BID_REJECTED)>@lang('Rejected')</option>
                                <option value="4" @selected(request()->status == Status::BID_WITHDRAW)>@lang('Withdrawn')</option>
                                <option value="2" @selected(request()->status == Status::BID_COMPLETED)>@lang('Done')</option>
                            </select>
                        </div>

                        <div class="flex-grow-1">
                            <input name="date" type="search"
                                class="datepicker-here form-control form--control bg--white pe-2 date-range"
                                placeholder="@lang('Start Date - End Date')" autocomplete="off" value="{{ request()->date }}">
                        </div>

                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--base w-100"><i class="las la-filter"></i>
                                @lang('Filter')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="dashboard-table">
            <table class="table table--responsive--md">
                <thead>
                    <tr>
                        <th>
                            <a
                                href="{{ route(
                                    'buyer.job.post.bids',
                                    array_merge(['id' => request()->id], request()->except(['sort', 'order']), [
                                        'sort' => 'is_shortlist',
                                        'order' => $sortColumn == 'is_shortlist' && $sortOrder == 'asc' ? 'desc' : 'asc',
                                    ]),
                                ) }}">
                                @lang('Shortlist')
                                @if ($sortColumn == 'is_shortlist')
                                    <i class="fas {{ $sortOrder == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>

                        <th> @lang('Freelancer') </th>
                        <th>@lang('Job')</th>
                        <th> @lang('Estimate Time') | @lang('Status') </th>
                        <th> @lang('Budget') </th>
                        <th> @lang('Skill Match') </th>
                        <th> @lang('Bidden at') </th>
                        <th> @lang('Action') </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bids as $bid)
                        <tr>
                            <td>
                                <button
                                    class="btn shortlist-btn btn---sm {{ $bid->is_shortlist ? 'btn-success' : 'btn-outline--primary' }} d-flex align-items-center gap-1"
                                    data-bid-id="{{ $bid->id }}">
                                    <i class="{{ $bid->is_shortlist ? 'fas fa-check-circle' : 'far fa-circle' }}"></i>
                                    <span>{{ $bid->is_shortlist ? 'Shortlisted' : 'Shortlist' }}</span>
                                </button>
                            </td>
                            <td>
                                <div>
                                    <span class="d-inline-flex align-items-center flex-wrap gap-1">
                                        {{ $bid->user->fullname }}
                                        @include('Template::partials.verification_badges', [
                                            'user' => $bid->user,
                                            'compact' => true,
                                        ])
                                    </span>
                                    <span class="small d-block">
                                        <a class="{{ $bid->user->work_profile_complete ? '' : 'disabled' }}"
                                            @if (!$bid->user->work_profile_complete) title="@lang('Profile not completed yet!')" @endif
                                            href="{{ $bid->user->work_profile_complete ? route('talent.explore', $bid->user->username) : 'javascript:void(0)' }}"
                                            target="{{ $bid->user->work_profile_complete ? '__blank' : '' }}">
                                            <span>@</span>{{ $bid->user->username }}
                                        </a>
                                    </span>
                                </div>
                            </td>
                            <td><a class="clamping" href="{{ route('buyer.job.post.view', $bid->job->id) }}"
                                    target="__blank">{{ __($bid->job->title) }}</a></td>
                            <td>
                                <div>
                                    <p class="clamping"> {{ __($bid->estimated_time) }}</p>

                                    @php echo $bid->statusBadge @endphp
                                </div>
                            </td>
                            <td>
                                <div>
                                    {{ showAmount($bid->bid_amount) }} <br>
                                    <sup class="text--primary"> [
                                        @if ($bid->job->custom_budget)
                                            @lang('Customized')
                                        @else
                                            @lang('Fixed')
                                        @endif
                                        ]
                                    </sup>
                                </div>
                            </td>
                            <td>
                                @php
                                    $skillMatch = matchSkill($bid->job, $bid->user);
                                    $barClass =
                                        $skillMatch >= 80
                                            ? 'bg--success'
                                            : ($skillMatch >= 50
                                                ? 'bg--warning'
                                                : 'bg--danger');
                                @endphp

                                <div class="mt-1 progress--div">
                                    <div class="progress">
                                        <div class="progress-bar {{ $barClass }}" role="progressbar"
                                            style="width: {{ $skillMatch }}%; min-width: 25px;"
                                            aria-valuenow="{{ $skillMatch }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ $skillMatch }}%
                                        </div>
                                    </div>
                                </div>

                            </td>
                            <td> {{ showDateTime($bid->created_at, 'd M, Y H:i a') }}</td>
                            <td>
                                <div class="action-btn">
                                    <button class="action-btn__icon">
                                        <i class="fa-solid fa-caret-down"></i>
                                    </button>
                                    <ul class="action-dropdown">
                                        <li class="action-dropdown__item moreModalBtn"
                                            data-freelancer="{{ $bid->user->fullname }}"
                                            data-quote="{{ __(@$bid->bid_quote) }}">
                                            <a class="action-dropdown__link" href="javascript:void(0)">
                                                <span class="text"><i class="las la-quote-left"></i>
                                                    @lang('Quote')</span>
                                            </a>
                                        </li>
                                        <li class="action-dropdown__item" title="@lang('As like interview for this job post!')">
                                            <a class="action-dropdown__link"
                                                href="{{ route('buyer.conversation.bid', $bid->id) }}">
                                                <span class="text"><i class="lab la-rocketchat"></i>
                                                    @lang('Chat')</span>
                                            </a>
                                        </li>
                                        @if ($bid->status == Status::BID_PENDING)
                                            <li class="action-dropdown__item bidConfirmationBtn"
                                                data-action="{{ route('buyer.job.post.hire.talent', $bid->id) }}"
                                                data-budget="{{ $bid->bid_amount }}"
                                                data-balance="{{ auth()->guard('buyer')->user()->balance }}">
                                                <a class="action-dropdown__link" href="javascript:void(0)">
                                                    <span class="text"><i class="las la-check"></i>
                                                        @lang('Hire Talent')</span>
                                                </a>
                                            </li>

                                            @if (gs('trial_task'))
                                                @if ($bid->task)
                                                    <li class="action-dropdown__item">
                                                        <a class="action-dropdown__link" href="javascript:void(0)" disabled>
                                                            <span class="text"><i class="las la-check-circle"></i>
                                                                @lang('Task Assigned')</span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="action-dropdown__item">
                                                        <a class="action-dropdown__link"
                                                            href="{{ route('buyer.trial.task.form', $bid->id) }}">
                                                            <span class="text"><i class="las la-check-circle"></i>
                                                                @lang('Assign Mini Task')</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                        @endif

                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center msg-center">
                                @include('Template::partials.empty', ['message' => 'Bid not found!'])
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($bids->hasPages())
                <div class="dashboard-table__bottom">
                    {{ paginateLinks($bids) }}
                </div>
            @endif
        </div>
    </div>


    <div class="modal custom--modal" id="moreModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <small> @lang('Bid from') <span class="fw-bold freelancer-name"></span></small>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p><i class="las la-quote-left"></i>
                    <p class="fw-bold bid-quote"></p><i class="las la-quote-right"></i></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn--sm"
                        data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom--modal" id="bidConfirmationModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <form method="POST">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-2">@lang('Confirmation of Project Assignment')</h5>
                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>

                        <p><i class="las la-quote-left"></i>
                        <p class="fw-bold"> @lang('By accepting this bid, you\'ll be selecting this freelancer for the project and rejecting all other proposals. Are you certain you want to proceed?')</p><i class="las la-quote-right"></i></p>

                        @if (gs('escrow_payment'))
                            <div class="bid-budget-info mb-3">
                                <h6>@lang('To proceed with this project, we are using the Escrow method for secure payment')</h6>
                                <p class="bid-budget-detail">
                                    @lang('The proposed budget for this project is:') <strong class="bid-budget"></strong>.
                                    @lang('This will ensure both you and the freelancer are protected during the transaction process.')
                                </p>
                            </div>
                        @endif

                        <div class="balanceInfo d-none">
                            <small class="py-2 text--danger d-block"><i class="las la-exclamation-circle"></i>
                                @lang('Also Your current balance is lower than talent payment for assign project.')
                                <a class="border radius--10px p-1"
                                    href="{{ route('buyer.deposit.index') }}">@lang('Deposit Now')</a>
                            </small>
                        </div>

                        <div class="text-end">
                            <button type="submit" data-bs-dismiss="modal"
                                class="btn btn--dark">@lang('Close')</button>
                            <button type="submit" class="btn btn--base btnConfirm">@lang('Confirm')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush


@push('script')
    <script>
        (function($) {
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

            //bid-related
            let curText = `{{ gs('cur_text') }}`;

            let moreModal = $("#moreModal");
            $(".moreModalBtn").on('click', function() {
                $('.freelancer-name').text($(this).data('freelancer'));
                $('.bid-quote').text($(this).data('quote'));
                moreModal.modal("show");
            });

            let confirmationModal = $("#bidConfirmationModal");
            $(".bidConfirmationBtn").on('click', function() {
                let data = $(this).data();
                confirmationModal.find('form').attr('action', `${data.action}`);
                confirmationModal.find('.bid-budget').text(`${parseFloat(data.budget).toFixed(2)} ${curText}`);

                @if (gs('escrow_payment'))
                    if (parseFloat(data.balance) < parseFloat(data.budget)) {
                        confirmationModal.find('.btnConfirm').attr('disabled', true);
                    } else {
                        confirmationModal.find('.btnConfirm').attr('disabled', false);
                    }
                @endif
                if (parseFloat(data.balance) < parseFloat(data.budget)) {
                    confirmationModal.find('.balanceInfo').removeClass('d-none');
                } else {
                    confirmationModal.find('.balanceInfo').addClass('d-none');
                }

                confirmationModal.modal("show");
            });




            $('.shortlist-btn').on('click', function(e) {
                e.preventDefault();
                toggleShortList($(this));
            });

            function toggleShortList(button) {
                let bidId = button.data('bid-id');
                let url = `{{ route('buyer.job.post.bids.shortlist', ['id' => ':id']) }}`.replace(':id', bidId);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "POST",
                    url: url,
                    success: function(response) {
                        if (response.status === 'success') {
                            let isShortlisted = response.data.shortlisted;
                            button
                                .data('shortlisted', isShortlisted)
                                .removeClass('btn-outline--primary btn-success')
                                .addClass(isShortlisted ? 'btn-success' : 'btn-outline--primary')
                                .html(
                                    isShortlisted ?
                                    '<i class="fas fa-check-circle"></i> <span>Shortlisted</span>' :
                                    '<i class="far fa-circle"></i> <span>Shortlist</span>'
                                );

                            notify('success', response.message);
                        } else {
                            notify('error', response.message);
                        }
                    },
                    error: function(response) {
                        notify('error', response.responseJSON.message || 'An error occurred.');
                    }
                });
            }

        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .shortlist-btn {
            transition: all 0.3s ease-in-out;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
        }

        .shortlist-btn i {
            font-size: 1rem;
        }

        .shortlist-btn.btn-outline--primary:hover {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        .shortlist-btn.btn-success:hover {
            background-color: #198754;
            color: #fff;
            border-color: #198754;
        }

        .sort-btn {
            text-decoration: none;
            color: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .sort-btn:hover {
            color: #007bff;
        }

        .sort-btn i {
            margin-left: 5px;
        }

        .btn---sm {
            padding: 6px 7px;
            font-weight: 600;
        }

        a.disabled {
            color: hsl(var(--secondary)) !important;
            cursor: none !important;
        }

        .progress--div {
            width: 100px;
        }

        .progress--div {
            width: 100px;
        }

        @media screen and (max-width: 991px) {
            .progress--div {
                width: 80px;
            }
        }

        @media screen and (max-width: 767px) {
            .progress--div {
                width: 100px;
            }
        }
    </style>
@endpush
