@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                {{-- Header --}}
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0">
                        @lang('Trial Task Details') — {{ __($task->title) }}
                    </h5>

                    <div>
                        @php echo $task->statusBadge @endphp
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4">

                        {{-- Left Column --}}
                        <div class="col-lg-6 task-details-wrapper">
                            <h6 class="text--primary mb-3">
                                <i class="las la-info-circle"></i> @lang('Task Information')
                            </h6>

                            <ul class="list-group list-group-flush">

                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>@lang('Amount')</strong>
                                    <span>{{ showAmount($task->amount) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>@lang('Escrow Amount')</strong>
                                    <span>{{ showAmount($task->escrow_amount) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>@lang('Deadline')</strong>
                                    <span>
                                        {{ $task->deadline ? showDateTime($task->deadline, 'd M, Y') : '—' }}
                                    </span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>@lang('Created At')</strong>
                                    <span>{{ showDateTime($task->created_at, 'd M, Y H:i') }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>@lang('Uploaded At')</strong>
                                    <span>
                                        {{ $task->uploaded_at ? showDateTime($task->uploaded_at) : '—' }}
                                    </span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>@lang('Upload Count')</strong>
                                    <span>{{ $task->upload_count }}</span>
                                </li>
                            </ul>
                        </div>

                        {{-- Right Column --}}
                        <div class="col-lg-6">
                            <h6 class="text--primary mb-3">
                                <i class="las la-users"></i> @lang('Participants')
                            </h6>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>@lang('Freelancer')</strong><br>
                                    {{ $task->user->fullname }}
                                    <span class="d-block small">
                                        <a href="{{ route('admin.users.detail', $task->user_id) }}" target="_blank">
                                            {{ $task->user->username }}
                                        </a>
                                    </span>
                                </li>

                                <li class="list-group-item">
                                    <strong>@lang('Buyer')</strong><br>
                                    {{ $task->buyer->fullname }}
                                    <span class="d-block small">
                                        <a href="{{ route('admin.buyers.detail', $task->buyer_id) }}" target="_blank">
                                            {{ $task->buyer->username }}
                                        </a>
                                    </span>
                                </li>

                                <li class="list-group-item">
                                    <strong>@lang('Job')</strong><br>
                                    <a href="{{ route('admin.jobs.details', $task->job_id) }}" target="_blank">
                                        {{ __($task->job->title) }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>

                    {{-- Description --}}
                    <div class="mt-4">
                        <h6 class="text--primary">
                            <i class="las la-align-left"></i> @lang('Task Description')
                        </h6>
                        <div class="border rounded p-3 mt-2">
                            @php echo $task->description @endphp
                        </div>
                    </div>

                    {{-- File --}}
                    @if ($task->task_file)
                        <div class="mt-4 text-center">
                            <div class="col-12 text-center">
                                <h5 class="text--primary mb-3">@lang('Submitted File')</h5>
                                <?php $file = $task->task_file;
                                $extension = pathinfo($file, PATHINFO_EXTENSION); ?>
                                <a href="{{ route('admin.trial.task.file.download', [$task->id, encrypt($file)]) }}"
                                    class="btn btn-outline--primary px-4">
                                    <i class="fas {{ getFileIcon(strtolower($extension)) }}"></i> @lang('Download')
                                    {{ ucfirst($extension) }}
                                </a>
                                <p class="text-muted mt-2">@lang('Click to download the task file')</p>
                            </div>
                        </div>
                    @endif

                    {{-- Freelancer Comment --}}
                    @if ($task->comments)
                        <div class="mt-4">
                            <h6 class="text--primary">@lang('Freelancer Comment')</h6>
                            <div class="border rounded p-3">
                                {{ $task->comments }}
                            </div>
                        </div>
                    @endif

                    {{-- Cancel Reason --}}
                    @if ($task->cancel_reason)
                        <div class="mt-4">
                            <h6 class="text--danger">
                                <i class="las la-ban"></i> @lang('Cancel Reason')
                            </h6>
                            <div class="border border--danger rounded p-3 text--danger mt-2">
                                {{ $task->cancel_reason }}
                            </div>
                        </div>
                    @endif

                    {{-- Report Reason --}}
                    @if ($task->report_reason)
                        <div class="mt-4">
                            <h6 class="text--danger">
                                <i class="las la-flag"></i> @lang('Report Reason')
                            </h6>
                            <div class="border border--danger rounded p-3 text--danger">
                                {{ $task->report_reason }}
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
@endsection

@push('style')
    <style>
        .card-header,
        .card-body {
            padding: 20px 30px !important;
        }

        .task-details-wrapper {
            border-right: 1px solid rgba(0, 0, 0, .1);
        }
    </style>
@endpush
