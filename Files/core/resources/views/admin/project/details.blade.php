@extends('admin.layouts.app')
@section('panel')
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap flex-md-nowrap mb-2">
            <h4 class="card-title mb-0">@lang('JOB'): {{ __($project->job->title) }}</h4>
            <div class="d-flex gap-2 flex-wrap justify-content-sm-end">
                @if ($project->status == Status::PROJECT_REPORTED)
                    <button class="btn btn--success confirmationBtn" data-question="@lang('Are you sure to complete or resolve this project?')"
                        data-action="{{ route('admin.project.complete', $project->id) }}">
                        <i class="las la-check-circle"></i> @lang('Complete')
                    </button>
                    <button class="btn btn--danger ms-2 confirmationBtn" data-question="@lang('Are you sure to reject this project?')"
                        data-action="{{ route('admin.project.reject', $project->id) }}">
                        <i class="las la-times-circle"></i> @lang('Reject')
                    </button>
                    <button class="btn btn--primary partialBtn" data-bs-toggle="modal" data-bs-target="#partialModal">
                        <i class="las la-check-circle"></i> @lang('Partial Complete')
                    </button>
                    @if (@$convId)
                        <a href="{{ route('admin.project.conversation', ['id' => @$convId, 'projectId' => @$project->id]) }}"
                            class="btn btn-outline--warning ms-2">
                            <i class="lab la-rocketchat"></i> @lang('Chat')
                        </a>
                    @endif
                @endif
            </div>
        </div>
        <div class="card-body">

            <div class="details-wrapper">
                <!-- Left Section: Buyer Information -->
                <div class="details-wrapper__item">
                    <div class="flex-grow-1">
                        <h5 class="text-uppercase text-primary mb-4"><i class="las la-user-secret"></i> @lang('Buyer Information')
                        </h5>
                        <div class="mb-3">
                            <strong>@lang('Buyer'):</strong>
                            <p class="text-muted">{{ $project->buyer->fullname }}</p>
                            <i class="las la-link"></i> <a
                                href="{{ route('admin.buyers.detail', $project->buyer_id) }}">{{ @$project->buyer->username }}</a>
                        </div>
                        <div class="mb-3">
                            <strong>@lang('Assigned At'):</strong>
                            <p class="text-muted">{{ showDateTime($project->created_at, 'd F Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>@lang('Uploaded At'):</strong>
                            <p class="text-muted">{{ showDateTime($project->uploaded_at, 'd F Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>@lang('Total Worked Time'):</strong>
                            <p class="text-muted">{{ formatTimeDiff($project->created_at, $project->uploaded_at) }}</p>
                        </div>
                        @if ($project->report_reason)
                            <div class="mb-3">
                                <strong class="text-danger">@lang('Report Reason'):</strong>
                                <p class="text-danger">{{ $project->report_reason }}</p>
                            </div>
                        @endif
                    </div>
                    @if ($project->buyerReview)
                        <div>
                            <h6 class="text-success">@lang('Freelancer\'s Rating for Buyer')</h6>
                            <div class="text-warning fs-5">
                                <ul class="rating-list">
                                    @php echo avgRating($project->buyerReview->rating) @endphp
                                </ul>
                                <p class="text-muted"> {{ $project->buyerReview->review }} </p>
                                <button class="confirmationBtn text--danger d-block btn--sm mt-2" type="button"
                                    data-action="{{ route('admin.project.buyer.rating.remove', $project->buyerReview->id) }}"
                                    data-question="@lang('Are you sure to remove this buyer rating & review?')"> <i class="las la-trash"></i> @lang('Remove')
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Section: Freelancer Information -->
                <div class="details-wrapper__item">
                    <div class="flex-grow-1">
                        <h5 class="text-uppercase text-primary mb-4"><i class="las la-user-tie"></i> @lang('Freelancer Information')</h5>
                        <div class="mb-3">
                            <strong>@lang('Freelancer'):</strong>
                            <p class="text-muted">{{ $project->user->fullname }}</p>
                            <i class="las la-link"></i> <a
                                href="{{ route('admin.users.detail', $project->user_id) }}">{{ @$project->user->username }}</a>
                        </div>
                        <div class="mb-3">
                            <strong>@lang('Project Status'):</strong>
                            <p>
                                @php echo $project->statusBadge @endphp
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
                            <p class="text-muted">{{ $project->bid->bid_quote ?? __('No quotes provided') }}</p>
                        </div>
                    </div>
                    @if (@$project->review)
                        <div>
                            <h6 class="text-success">@lang('Buyer Rating for the Freelancer')</h6>
                            <div class="text-warning fs-5">
                                <ul class="rating-list">
                                    @php echo avgRating($project->review->rating) @endphp
                                </ul>
                                <p class="text-muted"> {{ $project->review->review }} </p>
                                <button class="confirmationBtn text--danger d-block btn--sm mt-2" type="button"
                                    data-action="{{ route('admin.project.freelancer.rating.remove', $project->review->id) }}"
                                    data-question="@lang('Are you sure to remove this freelancer rating & review?')"> <i class="las la-trash"></i>
                                    @lang('Remove')</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($project->status == Status::PROJECT_PARTIAL_COMPLETED)
                <div class="card border border-warning shadow-sm mt-3">
                    <div class="card-header  bg-opacity-10 d-flex align-items-center">
                        <i class="las la-info-circle text--warning fs-4 me-2"></i>
                        <h6 class="mb-0 fw-semibold text--warning">
                            @lang('Partial Completion Reason')
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-warning mb-0 p-3">
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
                        <h5 class="text-primary mb-3">@lang('Project File')</h5>
                        <?php $file = $project->project_file;
                        $extension = pathinfo($file, PATHINFO_EXTENSION); ?>
                        <a href="{{ route('admin.project.file.download', [$project->id, encrypt($file)]) }}"
                            class="btn btn-outline-primary px-4">
                            <i class="fas {{ getFileIcon(strtolower($extension)) }}"></i> @lang('Download')
                            {{ ucfirst($extension) }}
                        </a>
                        <p class="text-muted mt-2">@lang('Click to download the project file')</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div id="partialModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>@lang('Partial Completed')</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.project.partial.complete', $project->id) }}" class="disableSubmission"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Freelancer Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="freelancer_amount" class="form-control"
                                    placeholder="@lang('Please provide freelancer percentage')">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Buyer Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="buyer_amount"
                                    placeholder="@lang('Please provide buyer percentage')" class="form-control">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Reason')</label>
                            <textarea class="form-control" placeholder="@lang('Reason')" name="reason" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ url()->previous() }}" />
@endpush

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

        .text-warning {
            color: #ffc107;
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

        .text-danger {
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

        .rating-display {
            font-size: 1.2rem;
            color: #ffc107;
        }

        .text-primary {
            color: #007bff !important;
        }

        .btn-outline-primary {
            font-size: 1rem;
            padding: 8px 12px;
        }

        .text-danger {
            font-weight: bold;
            color: #dc3545;
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

@push('script')
    <script>
        (function($) {
            "use strict";

            const total = 100;

            $('input[name="freelancer_amount"]').on('input', function() {
                let freelancer = parseFloat($(this).val());

                if (!isNaN(freelancer) && freelancer <= total) {
                    let buyer = total - freelancer;
                    $('input[name="buyer_amount"]')
                        .val(buyer)
                        .prop('readonly', true);
                } else {
                    $('input[name="buyer_amount"]')
                        .val('')
                        .prop('readonly', false);
                }
            });

            $('input[name="buyer_amount"]').on('input', function() {
                let buyer = parseFloat($(this).val());

                if (!isNaN(buyer) && buyer <= total) {
                    let freelancer = total - buyer;
                    $('input[name="freelancer_amount"]')
                        .val(freelancer)
                        .prop('readonly', true);
                } else {
                    $('input[name="freelancer_amount"]')
                        .val('')
                        .prop('readonly', false);
                }
            });

        })(jQuery);
    </script>
@endpush
