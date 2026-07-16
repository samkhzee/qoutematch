@extends('Template::layouts.master')
@section('content')
    <div class="table-wrapper">
        <div class="table-wrapper-header gap-3">
            <div class="show-filter mb-3 text-end">
                <button class="btn btn--base showFilterBtn btn--sm" type="button"><i class="las la-filter"></i>
                    @lang('Filter')</button>
            </div>
            <div class="responsive-filter-card my-4">
                <form>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="flex-grow-1">
                            <input class="form-control form--control" name="search" type="search"
                                value="{{ request()->search }}" placeholder="@lang('Search by Job')" autocomplete="off">
                        </div>
                        <div class="flex-grow-1">
                            <select class="form-control form--control select2" name="status"
                                data-minimum-results-for-search="-1">
                                <option value="">@lang('Job Status')</option>
                                <option value="1" @selected(request()->status == Status::PROJECT_RUNNING)>@lang('Running')</option>
                                <option value="2" @selected(request()->status == Status::PROJECT_COMPLETED)>@lang('Completed')</option>
                                <option value="4" @selected(request()->status == Status::PROJECT_BUYER_REVIEW)>@lang('Reviewing')</option>
                                <option value="5" @selected(request()->status == Status::PROJECT_REPORTED)>@lang('Reported')</option>
                                <option value="3" @selected(request()->status == Status::PROJECT_REJECTED)>@lang('Rejected')</option>
                                <option value="6" @selected(request()->status == Status::PROJECT_PARTIAL_COMPLETED)>@lang('Partial Complete')</option>
                            </select>
                        </div>

                        <div class="flex-grow-1">
                            <input class="datepicker-here form-control form--control bg--white date-range pe-2"
                                name="date" type="search" value="{{ request()->date }}" placeholder="@lang('Start Date - End Date')"
                                autocomplete="off">
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
            <table class="table--responsive--md table">
                <thead>
                    <tr>
                        <th> @lang('Job')</th>
                        <th> @lang('Buyer') </th>
                        <th> @lang('Budget') </th>
                        <th> @lang('Status') </th>
                        <th> @lang('Assigned at')</th>
                        <th> @lang('Action') </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td> <span class="clamping">{{ __($project->job->title) }}</span></td>
                            <td>
                                {{ $project->buyer->fullname }}
                            </td>
                            <td>
                                <div>
                                    {{ showAmount($project->bid->bid_amount) }}<br>
                                    <sup class="text--primary">
                                        [
                                        @if ($project->job->custom_budget)
                                            @lang('Customized')
                                        @else
                                            @lang('Fixed')
                                        @endif
                                        ]
                                    </sup>

                                </div>
                            </td>

                            <td> @php echo $project->statusBadge @endphp</td>
                            <td>{{ showDateTime($project->created_at, 'd M, Y H:i a') }}</td>
                            <td>
                                <div class="action-btn">
                                    <button class="action-btn__icon">
                                        <i class="fa-solid fa-caret-down"></i>
                                    </button>
                                    <ul class="action-dropdown">
                                        <li class="action-dropdown__item">

                                            <a class="action-dropdown__link @if ($project->status == Status::PROJECT_COMPLETED) disabled @endif"
                                                data-bs-toggle="tooltip" data-placement="top"
                                                href="@if ($project->status == Status::PROJECT_COMPLETED) javascript:void(0) @else {{ route('user.project.form', @$project->id) }} @endif "
                                                title="@lang('Upload your completed project file for reviewing')">

                                                <span class="text"><i class="las la-upload"></i>
                                                    @lang('Upload FIle')</span>
                                            </a>
                                        </li>
                                        <li class="action-dropdown__item">
                                            <a class="action-dropdown__link"
                                                href="{{ route('user.project.detail', @$project->id) }}">
                                                <span class="text"><i class="las la-desktop"></i>
                                                    @lang('Details')</span>
                                            </a>
                                        </li>

                                        <li class="action-dropdown__item">
                                            <a class="action-dropdown__link @if ($project->status == Status::PROJECT_COMPLETED) reviewRatingBtn @else disabled @endif"
                                                data-rating="{{ @$project->review?->rating }}"
                                                data-review="{{ @$project->review?->review }}"
                                                data-free-rating="{{ @$project->buyerReview?->rating }}"
                                                data-free-review="{{ @$project->buyerReview?->review }}"
                                                data-action="{{ route('user.project.store.review-rating', $project->id) }}"
                                                href="javascript:void(0)">
                                                <span class="text"><i class="las la-star"></i>
                                                    @lang('Review & Rating')</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="msg-center text-center" colspan="100%">
                                @include('Template::partials.empty', [
                                    'message' => 'Project not found!',
                                ])
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($projects->hasPages())
            <div class="card-footer">
                {{ paginateLinks($projects) }}
            </div>
        @endif
    </div>

    <!-- Project Rating & Review Modal -->
    <div class="modal custom--modal" id="ProjectReviewRatingModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Rating & Review')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>

                <!-- Buyer's Feedback for the Freelancer -->
                <div class="modal-body">
                    <div class="buyer-review-wrapper">
                        <p class="title">@lang('Buyer Feedback for You')</p>
                        <div class="form-group mb-3" user-select="none">
                            <label class="form--label">@lang('Rating')</label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input class="star-input" id="buyer-star{{ $i }}" name="buyer-rating"
                                        type="radio" value="{{ $i }}" disabled>
                                    <label class="pointer-none" for="buyer-star{{ $i }}">
                                        <i class="las la-star"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form--label">@lang('Review')</label>
                            <p class="buyer-review pointer-none"></p>
                        </div>
                    </div>
                    <!-- freelancer's feedback for the Buyer -->
                    <form id="projectReviewForm" method="POST">
                        @csrf
                        <div class="freelancer-review-wrapper">
                            <p class="title">@lang('Your Feedback for Buyer')</p>
                            <!-- Star Rating -->
                            <div class="form-group mb-3">
                                <label class="form--label">@lang('Rating the Buyer')</label>
                                <div class="star-rating">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input class="star-input" id="star{{ $i }}" name="rating"
                                            type="radio" value="{{ $i }}">
                                        <label for="star{{ $i }}"><i class="las la-star"></i></label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Review -->
                            <div class="form-group">
                                <label class="form--label" for="review">@lang('Write a Review')</label>
                                <textarea class="form--control" id="review" name="review" rows="4" placeholder="@lang('Share your experience')..."
                                    required></textarea>
                            </div>
                            <!-- Buttons -->
                            @if (!@$project->blocked_review)
                                <div class="text-end">
                                    <button class="btn btn--base w-100" type="submit">
                                        @lang('Submit')
                                    </button>
                                </div>
                            @else
                                <div class="text-end">
                                    <button class="btn btn--base w-100 disabled" type="button">
                                        @lang('Blocked Review')
                                    </button>
                                </div>
                            @endif
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
    <link type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}" rel="stylesheet">
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

            // review and rating

            $('.reviewRatingBtn').on('click', function() {
                let buyerRating = $(this).data('rating');
                let buyerReview = $(this).data('review');

                let freelancerRating = $(this).data('free-rating');
                let freelancerReview = $(this).data('free-review');
                let formAction = $(this).data('action');


                $('#projectReviewForm')[0].reset();
                $('.star-input').prop('checked', false);

                if (formAction) {
                    $('#projectReviewForm').attr('action', formAction);
                }


                if (buyerRating) {
                    $('.buyer-review-wrapper').show();
                    $(`.buyer-review-wrapper #buyer-star${buyerRating}`).prop('checked', true);
                    $('.buyer-review').text(buyerReview || 'No review provided');
                } else {
                    $('.buyer-review-wrapper').hide();
                }

                if (freelancerRating) {
                    $(`.freelancer-review-wrapper #star${freelancerRating}`).prop('checked', true);
                    $('#review').val(freelancerReview || '');
                }

                $('#ProjectReviewRatingModal').modal('show');
            });

        })(jQuery)
    </script>
@endpush

@push('style')
    <style>
        .dashboard-table,
        .dashboard-table tbody tr,
        .dashboard-table tbody td,
        .table-wrapper {
            overflow: visible !important;
        }

        .action-btn:has(.action-dropdown.show) {
            z-index: 100;
        }

        .action-btn .action-dropdown {
            z-index: 101;
        }

        .table-responsive {
            overflow-x: unset !important;
        }

        .pointer-none {
            pointer-events: none;
        }

        .star-rating {
            display: flex;
            justify-content: flex-start;
            gap: 8px;
            font-size: 1.5rem;
            color: #ffcc00;
            margin-bottom: 20px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffcc00;
        }

        .star-rating input:not(:checked)~label {
            color: #ccc;
        }

        #ProjectReviewRatingModal textarea {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 1rem;
            color: #555;
            resize: vertical;
            transition: border-color 0.3s;
        }

        #ProjectReviewRatingModal textarea:focus {
            border-color: #28a745;
            outline: none;
        }

        #ProjectReviewRatingModal .modal-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #e5e5e5;
            border-radius: 0 0 8px 8px;
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn--secondary {
            background-color: #e0e0e0;
            color: #333;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn--secondary:hover {
            background-color: #ccc;
            color: #333;
        }

        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: start;
        }

        .star-rating input {
            display: none;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffcc00;
        }

        /* Modal Styling */
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e5e5e5;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .buyer-review-wrapper .title {
            color: hsl(var(--heading-color));
            font-weight: 600;
            margin-bottom: 10px;
        }

        .freelancer-review-wrapper .title {
            color: hsl(var(--heading-color));
            font-weight: 600;
            margin-bottom: 10px;
        }

        .buyer-review-wrapper,
        .buyer-review-wrapper {
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 16px;
        }

        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: start;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 24px !important;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffcc00;
        }

        .buyer-review {
            background-color: #fafafa;
            padding: 10px;
            border-radius: 5px;
            color: #333;
            font-size: 0.95rem;
            line-height: 1.5;
        }
    </style>
@endpush
