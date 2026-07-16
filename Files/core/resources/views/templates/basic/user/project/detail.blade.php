@extends('Template::layouts.master')
@section('content')
    <div class="card custom--card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <h5 class="card-title py-2 mb-0">@lang('JOB'): {{ __($project->job->title) }}</h5>
            @if ($canReport ?? false)
                <button type="button" class="btn btn--danger btn--sm" data-bs-toggle="modal" data-bs-target="#reportProjectModal">
                    <i class="las la-flag"></i> @lang('Report / Open Dispute')
                </button>
            @endif
        </div>
        <div class="card-body">
            <div class="border-bottom mb-5">
                <h5 class="card-title py-2">@lang('JOB'): {{ __($project->job->title) }}</h5>
            </div>

            <div class="details-wrapper">
                <!-- Left Section: Freelancer Information -->
                <div class="details-wrapper__item">
                    <div class="flex-grow-1">
                        <h5 class="text-uppercase text--primary mb-4"><i class="las la-user-tie"></i> @lang('Freelancer Information')
                        </h5>

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

                        @if ($project->status == Status::PROJECT_COMPLETED)
                            <div class="mb-3">
                                <strong>@lang('Uploaded At'):</strong>
                                <p class="text-muted">{{ showDateTime($project->uploaded_at, 'd F Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Total Worked Time'):</strong>
                                <p class="text-muted">{{ formatTimeDiff($project->created_at, $project->uploaded_at) }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <strong>@lang('Comment'):</strong>
                                <p class="text-muted">{{ $project->comments ?? __('No quote found') }}</p>
                            </div>
                        @endif

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
                            <h6 class="text--success">@lang('Buyer\'s Rating for You')</h6>
                            <div class="text-warning fs-5">
                                <ul class="rating-list">
                                    @php echo avgRating($project->review->rating) @endphp
                                </ul>
                                <p class="mt-2"><i class="las la-quote-left"></i> {{ __($project->review->review) }}
                                    <i class="las la-quote-right"></i>
                                </p>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Right Section: Buyer Information -->
                <div class="details-wrapper__item">
                    <div class="flex-grow-1">
                        <h5 class="text-uppercase text--primary mb-4"><i class="las la-user-secret"></i>
                            @lang('Buyer Information')</h5>
                        <div class="mb-3">
                            <strong>@lang('Buyer'):</strong>
                            <p class="text-muted">{{ $project->buyer->fullname }}</p>
                        </div>
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

                    </div>
                    @if ($project->buyerReview)
                        <div>
                            <h6 class="text--success">@lang('Your Rating & Review for Buyer')</h6>
                            <div class="text-warning fs-5">
                                <ul class="rating-list">
                                    @php echo avgRating($project->buyerReview->rating) @endphp
                                </ul>
                                @if (@$project->buyerReview?->review)
                                    <p class="mt-2"><i class="las la-quote-left"></i>
                                        {{ __(@$project->buyerReview?->review) }} <i class="las la-quote-right"></i>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($project->status == Status::PROJECT_PARTIAL_COMPLETED)
                <div class="card border-warning shadow-sm mt-4">
                    <div class="card-header bg-opacity-10 d-flex text-center align-items-center">
                        <i class="las la-info-circle text--warning fs-4 me-2"></i>
                        <h6 class="mb-0 fw-semibold text--warning">
                            @lang('Partial Completion Reason')
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="alert alert--warning mb-0">
                            <p class="mb-0 text-dark">
                                {{ __($project->partial_approve_reason) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif


            @if ($project->uploaded_at)
                <!-- Bottom Section: File Download -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <h5 class="mb-3">@lang('Project File')</h5>
                        <?php $file = $project->project_file;
                        $extension = pathinfo($file, PATHINFO_EXTENSION); ?>
                        <a href="{{ route('user.project.file.download', [$project->id, encrypt($file)]) }}"
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

    @if ($canReport ?? false)
        <div class="modal custom--modal" id="reportProjectModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Report / Open Dispute')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('user.project.report', $project->id) }}">
                        @csrf
                        <div class="modal-body">
                            @include('Template::partials.dispute_type_field')
                            <div class="form-group">
                                <label for="reportReason" class="form--label">@lang('Describe the issue')</label>
                                <textarea class="form--control" id="reportReason" name="report_reason" rows="4"
                                    placeholder="@lang('Explain what went wrong')..." required>{{ old('report_reason') }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--danger">@lang('Submit Dispute')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

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


        .rating-list {
            display: inline-flex;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .rating-list__item {
            color: #ffcc00;
            margin-right: 3px;
        }

        .rating-list__item .la-star,
        .rating-list__item .fa-star-half-alt,
        .rating-list__item .lar.la-star {
            font-size: 1.2rem;
        }

        .rating-list__item .la-star {
            color: #ffcc00;
        }

        .rating-list__item .fa-star-half-alt {
            color: #ffcc00;
        }

        .rating-list__item .lar.la-star {
            color: #ccc;
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
            padding: 15px 40px 30px !important;
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
