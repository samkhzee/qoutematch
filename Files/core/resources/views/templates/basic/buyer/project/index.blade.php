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
                                <option value="1" @selected(request()->status == Status::PROJECT_RUNNING)>@lang('Running')</option>
                                <option value="2" @selected(request()->status == Status::PROJECT_COMPLETED)>@lang('Completed')</option>
                                <option value="4" @selected(request()->status == Status::PROJECT_BUYER_REVIEW)>@lang('Reviewing')</option>
                                <option value="5" @selected(request()->status == Status::PROJECT_REPORTED)>@lang('Reported')</option>
                                <option value="3" @selected(request()->status == Status::PROJECT_REJECTED)>@lang('Rejected')</option>
                                <option value="6" @selected(request()->status == Status::PROJECT_PARTIAL_COMPLETED)>@lang('Partial Complete')</option>
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

        <div class="table-responsive">
            <table class="table table--responsive--md">
                <thead>
                    <tr>
                        <th>@lang('Job')</th>
                        <th> @lang('Freelancer') </th>
                        <th> @lang('Estimate Time') </th>
                        <th> @lang('Budget') </th>
                        <th> @lang('Status') </th>
                        <th> @lang('Assigned at')</th>
                        <th> @lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td><span class="clamping">{{ __($project->job->title) }}</span></td>
                            <td>
                                <div>
                                    <span class="d-inline-flex align-items-center flex-wrap gap-1">
                                        {{ $project->user->fullname }}
                                        @include('Template::partials.verification_badges', [
                                            'user' => $project->user,
                                            'compact' => true,
                                        ])
                                    </span>
                                    <a class="d-block"
                                        href="{{ route('talent.explore', $project->user->username) }}">{{ $project->user->username }}</a>
                                </div>
                            </td>
                            <td><span class="clamping">{{ __($project->bid->estimated_time) }}</span></td>
                            <td>
                                <div>
                                    {{ showAmount($project->bid->bid_amount) }}<br>
                                    @if ($project->job->custom_budget)
                                        <sup class="text--info"> [ @lang('Customized') ]
                                        @else
                                            <sup class="text--primary"> [ @lang('Fixed') ]
                                    @endif
                                    </sup>
                                </div>
                            </td>
                            <td> @php echo $project->statusBadge @endphp</td>
                            <td>{{ showDateTime($project->created_at, 'd M, Y H:i a') }}</td>
                            <td>
                                <div class="btn-group gap-2">
                                    <a href="{{ $project->status != Status::PROJECT_REJECTED ? route('buyer.project.detail', $project->id) : '#' }}"
                                        class="view-btn @if ($project->status == Status::PROJECT_REJECTED) disabled @endif"
                                        title="@if ($project->status == Status::PROJECT_REJECTED) @lang('Disabled') @else @lang('Project Details') @endif"
                                        data-bs-toggle="tooltip">
                                        <i class="las la-desktop"></i>
                                    </a>

                                    <button type="button"
                                        class="view-btn @if (
                                            !in_array($project->status, [Status::PROJECT_BUYER_REVIEW, Status::PROJECT_PARTIAL_COMPLETED]) &&
                                                $project->upload_count > 0) reviewRatingBtn @else disabled @endif"title="@lang('Review & Rating')"
                                        data-scores='@json(@$project->review?->scores ?? [])'
                                        data-rating="{{ @$project->review?->rating }}"
                                        data-review="{{ @$project->review?->review }}"
                                        data-free-rating="{{ @$project->buyerReview?->rating }}"
                                        data-free-review="{{ @$project->buyerReview?->review }}"
                                        data-action="{{ route('buyer.project.update.review-rating', $project->id) }}">
                                        <i class="las la-star"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center msg-center">
                                @include('Template::partials.empty', [
                                    'message' => request()->status || request()->search || request()->date
                                        ? 'No projects match your filters.'
                                        : 'No projects yet. Projects appear here after you hire a provider from a request.',
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
                    <h5 class="modal-title" id="projectCompletedLabel">@lang('Update Your Rating & Review')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST" id="projectReviewForm">
                    @csrf
                    <div class="modal-body">
                        <div class="buyer-review-wrapper">
                            <h5>@lang('Your feedback for freelancer')</h5>
                            <p class="text-muted small mb-3">@lang('Rate each area from 1 to 5 stars.')</p>
                            @include('Template::partials.structured_review_form', ['prefix' => 'scores'])

                            <!-- Review -->
                            <div class="form-group">
                                <label for="review" class="form--label">@lang('Write a Review')</label>
                                <textarea name="review" class="form--control" id="review" rows="4" placeholder="@lang('Share your experience')..."
                                    required></textarea>
                            </div>
                            @if (!@$project->blocked_review)
                                <button type="submit" class="btn btn--base">@lang('Update Review & Rating')</button>
                            @else
                                <button type="button" class="btn btn--base disabled">
                                    @lang('Blocked Review')
                                </button>
                            @endif
                        </div>


                    </div>
                </form>
                <div class="freelancer-review-wrapper p-4">
                    <h5>@lang('Freelancer feedback for you')</h5>
                    <div class="form-group mb-3" user-select="none">
                        <label class="form--label">@lang('You rated by freelancer')</label>
                        <div class="star-rating freelancer-star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" name="freelancer_rating_display" value="{{ $i }}"
                                    id="freelancer_star{{ $i }}" class="star-input" disabled>
                                <label class="pointer-none" for="freelancer_star{{ $i }}"><i
                                        class="las la-star"></i></label>
                            @endfor
                        </div>
                    </div>

                    <!-- Review -->
                    <div class="form-group">
                        <label for="review" class="form--label">@lang('Freelancer Review')</label>
                        <p class="freelancer-review"></p>
                    </div>
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
                let scores = $(this).data('scores') || {};
                let review = $(this).data('review');

                let freelancerRating = $(this).data('free-rating');
                let freelancerReview = $(this).data('free-review');

                $('#projectReviewForm')[0].reset();
                $('.structured-review-form .star-input').prop('checked', false);
                Object.keys(scores).forEach(function(key) {
                    $(`#scores_${key}_star${scores[key]}`).prop('checked', true);
                });
                $('#review').val(review || '');
                let formAction = $(this).data('action');
                if (formAction) {
                    $('#projectReviewForm').attr('action', formAction);
                }

                //freelancer
                $('.freelancer-review-wrapper .freelancer-star-rating input').prop('checked', false);
                if (freelancerRating) {
                    $('.freelancer-review-wrapper').show();
                    $(`.freelancer-review-wrapper #freelancer_star${freelancerRating}`).prop('checked', true);
                    $('.freelancer-review').text(freelancerReview || 'No review provided');
                } else {
                    $('.freelancer-review-wrapper').hide();
                }
                $('#ProjectReviewRatingModal').modal('show');
            });

        })(jQuery)
    </script>
@endpush

@push('style')
    <style>
        .pointer-none {
            pointer-events: none;
        }

        /* Star Rating Styles */
        .star-rating {
            display: flex;
            justify-content: flex-start;
            gap: 8px;
            font-size: 1.5rem;
            color: #ffcc00;
            margin: 10px 0 20px;
        }

        .star-rating input {
            display: none;
            /* Hide radio inputs */
        }

        .star-rating label {
            cursor: pointer;
            transition: color 0.3s;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffcc00;
            /* Yellow stars */
        }

        .star-rating input:not(:checked)~label {
            color: #ccc;
            /* Gray stars for unselected */
        }

        /* Textarea Styling */
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
            /* Green focus border */
            outline: none;
        }

        /* Form Footer Buttons */
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

        /* Star Rating Container */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: start;
        }

        .star-rating input {
            display: none;
            /* Hide radio buttons */
        }

        .star-rating label {
            font-size: 24px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffcc00;
            /* Highlight stars */
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

        .buyer-review-wrapper,
        .freelancer-review-wrapper {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background-color: #fafafa;
        }

        h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: start;
        }

        .star-rating input {
            display: none;
            /* Hide radio buttons */
        }

        .star-rating label {
            font-size: 24px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffcc00;
            /* Highlight stars */
        }




        .freelancer-review {
            background-color: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #333;
            font-size: 0.95rem;
            line-height: 1.5;
        }
    </style>
@endpush
