@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $dispute->subject }}</h5>
                    @php echo $dispute->statusBadge @endphp
                </div>
                <div class="card-body">
                    <div class="row gy-3 mb-4">
                        <div class="col-md-6">
                            <span class="text-muted d-block">@lang('Type')</span>
                            <strong>{{ $dispute->typeLabel }}</strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block">@lang('Raised By')</span>
                            <strong>{{ ucfirst($dispute->raised_by) }}</strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block">@lang('Customer')</span>
                            @if ($dispute->buyer)
                                <a href="{{ route('admin.buyers.detail', $dispute->buyer_id) }}">{{ $dispute->buyer->fullname }}
                                    (<span>@</span>{{ $dispute->buyer->username }})</a>
                            @else
                                —
                            @endif
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block">@lang('Provider')</span>
                            @if ($dispute->user)
                                <a href="{{ route('admin.users.detail', $dispute->user_id) }}">{{ $dispute->user->fullname }}
                                    (<span>@</span>{{ $dispute->user->username }})</a>
                            @else
                                —
                            @endif
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block">@lang('Request')</span>
                            @if ($dispute->job)
                                <a href="{{ route('admin.jobs.details', $dispute->job_id) }}">{{ $dispute->job->title }}</a>
                            @else
                                —
                            @endif
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block">@lang('Quote')</span>
                            @if ($dispute->bid)
                                <a href="{{ route('admin.bids.detail', $dispute->bid_id) }}">
                                    {{ showAmount($dispute->bid->bid_amount) }} — @lang('View quote')
                                </a>
                            @else
                                —
                            @endif
                        </div>
                        @if ($dispute->project_id)
                            <div class="col-md-6">
                                <span class="text-muted d-block">@lang('Project')</span>
                                <a href="{{ route('admin.project.details', $dispute->project_id) }}">@lang('View project')</a>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <span class="text-muted d-block">@lang('Submitted')</span>
                            <strong>{{ showDateTime($dispute->created_at) }}</strong>
                        </div>
                    </div>

                    <h6 class="mb-2">@lang('Description')</h6>
                    <div class="content-panel">
                        {!! nl2br(e($dispute->description)) !!}
                    </div>

                    @if ($dispute->admin_note)
                        <h6 class="mb-2">@lang('Admin Note')</h6>
                        <div class="content-panel content-panel--plain">
                            {!! nl2br(e($dispute->admin_note)) !!}
                        </div>
                    @endif

                    @if ($dispute->resolved_at)
                        <p class="text-muted small mb-0">
                            @lang('Closed'): {{ showDateTime($dispute->resolved_at) }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @if (!in_array((int) $dispute->status, [Status::DISPUTE_RESOLVED, Status::DISPUTE_REJECTED], true))
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Moderation')</h5>
                    </div>
                    <div class="card-body">
                        @if ((int) $dispute->status === Status::DISPUTE_OPEN)
                            <form action="{{ route('admin.disputes.in_review', $dispute->id) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="form-group">
                                    <label>@lang('Note (optional)')</label>
                                    <textarea name="admin_note" class="form-control" rows="3">{{ old('admin_note', $dispute->admin_note) }}</textarea>
                                </div>
                                <button type="submit" class="btn btn--primary w-100">@lang('Mark In Review')</button>
                            </form>
                        @endif

                        <form action="{{ route('admin.disputes.resolve', $dispute->id) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="form-group">
                                <label>@lang('Resolution note')</label>
                                <textarea name="admin_note" class="form-control" rows="3">{{ old('admin_note') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn--success w-100">@lang('Resolve Dispute')</button>
                        </form>

                        <form action="{{ route('admin.disputes.reject', $dispute->id) }}" method="POST"
                            onsubmit="return confirm(@json(__('Reject this dispute? A note is required.')))">
                            @csrf
                            <div class="form-group">
                                <label>@lang('Rejection reason') *</label>
                                <textarea name="admin_note" class="form-control" rows="3" required>{{ old('admin_note') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn--danger w-100">@lang('Reject & Close')</button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Quick Links')</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.disputes.index') }}" class="btn btn-outline--primary btn-sm">@lang('All Disputes')</a>
                        <a href="{{ route('admin.marketplace.dashboard') }}" class="btn btn-outline--primary btn-sm">@lang('Marketplace Dashboard')</a>
                        @if ($dispute->project_id)
                            <a href="{{ route('admin.project.details', $dispute->project_id) }}"
                                class="btn btn-outline--dark btn-sm">@lang('Project Details')</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
