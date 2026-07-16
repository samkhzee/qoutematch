@extends('Template::layouts.buyer_master')
@section('content')
    <div class="account-section">
        <div class="card custom--card">
            <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap flex-md-nowrap">
                <h5 class="card-title mb-0">@lang('Task'): {{ __($task->title) }}</h5>
                <div class="d-flex gap-2 flex-wrap justify-content-sm-end">
                    <button class="btn btn--success btn--sm" data-bs-toggle="modal" data-bs-target="#projectCompletedModal">
                        @lang('Complete')
                    </button>
                    <button class="btn btn--danger btn--sm ms-2" data-bs-toggle="modal" data-bs-target="#reportProjectModal">
                        @lang('Report')
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="details-wrapper">
                    <div class="details-wrapper__item">
                        <div class="flex-grow-1">
                            <h5 class="text-uppercase text--primary mb-4"><i class="las la-user-secret"></i>
                                @lang('Buyer Information')
                            </h5>
                            <div class="mb-3">
                                <strong>@lang('Task Status'):</strong>
                                <p>
                                    @php echo $task->statusBadge @endphp
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Assigned At'):</strong>
                                <p class="text-muted">{{ showDateTime($task->created_at, 'd F Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Uploaded At'):</strong>
                                <p class="text-muted">
                                    @if ($task->uploaded_at)
                                        {{ showDateTime($task->uploaded_at, 'd F Y') }}
                                    @else
                                        <small class="text--base">...</small>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Total Worked Time'):</strong>
                                <p class="text-muted">
                                    @if ($task->uploaded_at)
                                        {{ formatTimeDiff($task->created_at, $task->uploaded_at) }}
                                    @else
                                        <small class="text--base">...</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section: Freelancer Information -->
                    <div class="details-wrapper__item">
                        <div class="flex-grow-1">
                            <h5 class="text-uppercase text--primary mb-4"><i class="las la-user-tie"></i> @lang('Freelancer Information')
                            </h5>
                            <div class="mb-3">
                                <strong>@lang('Freelancer'):</strong>
                                <p class="text-muted">{{ $task->user->fullname }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Deadline'):</strong>
                                <p class="text-muted">{{ $task->deadline }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>@lang('Amount'):</strong>
                                <p class="text-muted">{{ showAmount($task->amount) }}</p>
                            </div>
                            @if ($task->upload_count)
                                <div class="mb-3">
                                    <strong>@lang('Total Uploaded'):</strong>
                                    <p class="text--muted">{{ $task->upload_count }} @lang('Times')</p>
                                </div>
                            @endif

                            @if ($task->report_reason)
                                <div class="mb-3">
                                    <strong class="text--danger">@lang('Report Reason'):</strong>
                                    <p class="text--danger">{{ $task->report_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Bottom Section: File Download -->
                @if ($task->uploaded_at)
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <h5 class="mb-3">@lang('Task File')</h5>
                            <?php $file = $task->task_file;
                            $extension = pathinfo($file, PATHINFO_EXTENSION); ?>
                            <a href="{{ route('buyer.trial.task.file.download', [$task->id, encrypt($file)]) }}"
                                class="btn btn-outline--base px-4">
                                <i class="fas {{ getFileIcon(strtolower($extension)) }}"></i> @lang('Download')
                                {{ ucfirst($extension) }}
                            </a>
                            <p class="text-muted mt-2">@lang('Click to download the task file')</p>
                        </div>
                    </div>
                @endif

                @if ($task->comments)
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <h5 class="mb-3">@lang('Freelancer Comments')</h5>

                            <p class="text-muted mt-2">{{ __($task->comments) }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Project Completed Modal -->
        <div class="modal custom--modal" id="projectCompletedModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="projectCompletedLabel">@lang('Confirm Task Completion')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('buyer.trial.task.complete', $task->id) }}">
                        @csrf
                        <div class="modal-body">
                            <p class="mb-3">@lang('Are you sure you want to mark this task as completed?')</p>
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
                        <h5 class="modal-title" id="reportProjectLabel">@lang('Report this task')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('buyer.trial.task.report', $task->id) }}">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="reportReason" class="form--label">@lang('Reason for Reporting')</label>
                                <textarea class="form--control" id="reportReason" name="report_reason" rows="4"
                                    placeholder="@lang('Describe the issue')..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type= "submit" class= "btn btn--danger">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
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
