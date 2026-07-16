@extends('Template::layouts.buyer_master')
@section('content')
    <div class="account-section">
        <div class="card custom--card">
            <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap flex-md-nowrap">
                <h5 class="card-title mb-0">@lang('JOB'): {{ __($project->job->title) }}</h5>
                @if ($project->status == Status::PROJECT_BUYER_REVIEW && ($canReport ?? false))
                    <div class="d-flex gap-2 flex-wrap justify-content-sm-end">
                        <button class="btn btn--success btn--sm" data-bs-toggle="modal"
                            data-bs-target="#projectCompletedModal">
                            @lang('Complete')
                        </button>
                        <button class="btn btn--danger btn--sm ms-2" data-bs-toggle="modal"
                            data-bs-target="#reportProjectModal">
                            @lang('Report / Open Dispute')
                        </button>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div class="details-wrapper">
                    <div class="details-wrapper__item">
                        <div class="flex-grow-1">
                            <h5 class="text-uppercase text--primary mb-4"><i class="las la-user-secret"></i>
                                @lang('Buyer Information')
                            </h5>
                            <div class="mb-3">
                                <strong>@lang('Project Status'):</strong>
                                <p>
                                    @php echo $project->statusBadge @endphp
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Assigned At'):</strong>
                                <p class="text-muted">{{ showDateTime($project->created_at, 'd F Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Uploaded At'):</strong>
                                <p class="text-muted">
                                    @if ($project->uploaded_at)
                                        {{ showDateTime($project->uploaded_at, 'd F Y') }}
                                    @else
                                        <small class="text--base">...</small>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Total Worked Time'):</strong>
                                <p class="text-muted">
                                    @if ($project->uploaded_at)
                                        {{ formatTimeDiff($project->created_at, $project->uploaded_at) }}
                                    @else
                                        <small class="text--base">...</small>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Project Review'):</strong>
                                <p class="text-muted">{{ $project->comments ?? __('No review provided') }}</p>
                            </div>
                        </div>
                        @if ($project->buyerReview)
                            <div>
                                <h6 class="text--success mb-1">@lang('Freelancer\'s Rating for You')</h6>
                                <div class="text-warning fs-5">
                                    <ul class="rating-list">
                                        @php echo avgRating($project->buyerReview->rating) @endphp
                                    </ul>
                                    @if (@$project->buyerReview?->review)
                                        <p class="mt-2"><i class="las la-quote-left"></i>
                                            {{ __(@$project->buyerReview?->review) }} <i class="las la-quote-right"></i></p>
                                    @endif

                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Section: Freelancer Information -->
                    <div class="details-wrapper__item">
                        <div class="flex-grow-1">
                            <h5 class="text-uppercase text--primary mb-4"><i class="las la-user-tie"></i> @lang('Freelancer Information')
                            </h5>
                            <div class="mb-3">
                                <strong>@lang('Freelancer'):</strong>
                                <p class="text-muted d-flex align-items-center flex-wrap gap-1 mb-0">
                                    {{ $project->user->fullname }}
                                    @include('Template::partials.verification_badges', ['user' => $project->user])
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Estimated Time'):</strong>
                                <p class="text-muted">{{ $project->bid->estimated_time }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Bid Amount'):</strong>
                                <p class="text-muted">{{ showAmount($project->bid->bid_amount) }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Bid Quotes'):</strong>
                                <p class="text-muted">{{ $project->bid->bid_quote ?? __('No comments provided') }}</p>
                            </div>
                            @if ($project->upload_count)
                                <div class="mb-3">
                                    <strong>@lang('Total Uploaded'):</strong>
                                    <p class="text--muted">{{ $project->upload_count }} @lang('Times')</p>
                                </div>
                            @endif

                            @if ($project->report_reason)
                                <div class="mb-3">
                                    <strong class="text--danger">@lang('Report Reason'):</strong>
                                    <p class="text--danger">{{ $project->report_reason }}</p>
                                </div>
                            @endif
                        </div>

                        @if ($project->review)
                            <div>
                                <h6 class="text--success mb-1">@lang('Your Rating & Review for the Freelancer')</h6>
                                <div class="text-warning fs-5">
                                    <ul class="rating-list">
                                        @php echo avgRating($project->review->rating) @endphp
                                    </ul>

                                    @if (@$project->review?->review)
                                        <p class="mt-2"><i class="las la-quote-left"></i>
                                            {{ __(@$project->review?->review) }} <i class="las la-quote-right"></i></p>
                                    @endif
                                    @include('Template::partials.structured_review_scores', ['scores' => $project->review->scores ?? []])
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($project->status == Status::PROJECT_PARTIAL_COMPLETED)
                    <div class="card border-warning shadow-sm mt-4">
                        <div class="card-header bg-opacity-10 d-flex align-items-center">
                            <i class="las la-info-circle text-warning fs-4 me-2"></i>
                            <h6 class="mb-0 fw-semibold text-warning">
                                @lang('Partial Completion Reason')
                            </h6>
                        </div>

                        <div class="card-body">
                            <div class="alert alert-warning mb-0">
                                <p class="mb-0 text-dark">
                                    {{ __($project->partial_approve_reason) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif


                <!-- Bottom Section: File Download -->
                @if ($project->uploaded_at)
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <h5 class="mb-3">@lang('Project File')</h5>
                            <?php $file = $project->project_file;
                            $extension = pathinfo($file, PATHINFO_EXTENSION); ?>
                            <a href="{{ route('buyer.project.file.download', [$project->id, encrypt($file)]) }}"
                                class="btn btn-outline--base px-4">
                                <i class="fas {{ getFileIcon(strtolower($extension)) }}"></i> @lang('Download')
                                {{ ucfirst($extension) }}
                            </a>
                            <p class="text-muted mt-2">@lang('Click to download the project file')</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @include('Template::partials.dispute_project_panel', ['dispute' => $dispute ?? null, 'disputeDetailRoute' => $disputeDetailRoute ?? null])

        <!-- Project Completed Modal -->
        <div class="modal custom--modal" id="projectCompletedModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="projectCompletedLabel">@lang('Confirm Project Completion')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('buyer.project.complete', $project->id) }}">
                        @csrf
                        <div class="modal-body">
                            <p class="mb-3">@lang('Are you sure you want to mark this project as completed?')</p>
                            <p class="text-muted small mb-3">@lang('Rate each area from 1 to 5 stars.')</p>
                            @include('Template::partials.structured_review_form', ['prefix' => 'scores'])

                            <div class="form-group">
                                <label for="review" class="form--label">@lang('Write a Review')</label>
                                <textarea name="review" class="form--control" id="review" rows="4" placeholder="@lang('Share your experience')..."
                                    required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--base">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Report Project Modal -->
        <div class="modal custom--modal" id="reportProjectModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportProjectLabel">@lang('Report / Open Dispute')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('buyer.project.report', $project->id) }}">
                        @csrf
                        <div class="modal-body">
                            @include('Template::partials.dispute_type_field')
                            <div class="form-group">
                                <label for="reportReason" class="form--label">@lang('Reason for Reporting')</label>
                                <textarea class="form--control" id="reportReason" name="report_reason" rows="4"
                                    placeholder="@lang('Describe the issue')..." required>{{ old('report_reason') }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--danger">@lang('Submit Dispute')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('style')
        <style>
            .card {
                border-radius: 10px;
                overflow: hidden;
                background: #fff;
            }

            h5.text-uppercase {
                font-size: 1.1rem;
                font-weight: 600;
            }

            .info-row strong {
                font-weight: 600;
                color: #555;
            }

            .text-muted {
                font-size: 0.95rem;
                color: #6c757d !important;
            }


            .badge {
                font-size: 0.9rem;
                padding: 6px 10px;
            }

            .btn-outline-primary {
                font-size: 1rem;
                font-weight: 600;
                border-radius: 5px;
            }

            .text--danger {
                font-weight: 600;
                font-size: 0.95rem;
            }

            .custom-section {
                padding: 15px;
                background-color: #f9f9f9;
                border: 1px solid #e0e0e0;
                border-radius: 5px;
            }

            .section-title {
                font-size: 1.1rem;
                font-weight: bold;
            }

            .info-row {
                margin-bottom: 10px;
            }

            .info-label {
                font-weight: 600;
                color: #555;
            }

            .info-value {
                font-weight: 400;
                color: #333;
            }

            .btn-outline-primary {
                font-size: 1rem;
                padding: 8px 12px;
            }

            .flex-column {
                padding-left: 30px !important;
            }

            @media (max-width: 991px) {
                .flex-column {
                    padding-left: 0 !important;
                }
            }

            .star-rating {
                display: flex;
                flex-direction: row-reverse;
                justify-content: start;
                font-size: 3rem;
                cursor: pointer;
            }

            .star-rating input {
                display: none;
            }

            .star-rating label {
                color: #ddd;
                transition: color 0.3s;
            }

            .star-rating input:checked~label,
            .star-rating label:hover,
            .star-rating label:hover~label {
                color: #f5b301;
            }

            .visually-hidden {
                position: absolute;
                width: 1px;
                height: 1px;
                margin: -1px;
                border: 0;
                padding: 0;
                white-space: nowrap;
                clip-path: inset(100%);
                clip: rect(0 0 0 0);
                overflow: hidden;
            }


            .details-wrapper {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            }

            @media (max-width:991px) {
                .details-wrapper {
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                }
            }

            .details-wrapper__item {
                border-right: 1px solid rgba(0, 0, 0, .1);
            }

            .details-wrapper__item:last-child {
                border-right: 0;
                padding-left: 25px;
            }

            .card-header,
            .card-body {
                padding: 20px 30px !important;
            }

            @media (max-width:575px) {
                .details-wrapper {
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                }

                .details-wrapper__item {
                    border-right: 0;
                }

                .details-wrapper__item:last-child {
                    padding-left: 0px;
                    border-top: 1px solid rgba(0, 0, 0, .1);
                    padding-top: 20px;
                }

                .card-header,
                .card-body {
                    padding: 10px 20px !important;
                }
            }
        </style>
    @endpush
