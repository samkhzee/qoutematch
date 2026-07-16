@extends('Template::layouts.master')
@section('content')
    <div class="card custom--card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
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
                    <strong>{{ $dispute->buyer?->fullname ?? '—' }}</strong>
                </div>
                <div class="col-md-6">
                    <span class="text-muted d-block">@lang('Request')</span>
                    <strong>{{ $dispute->job?->title ?? '—' }}</strong>
                </div>
                <div class="col-md-6">
                    <span class="text-muted d-block">@lang('Quote Amount')</span>
                    <strong>{{ $dispute->bid ? showAmount($dispute->bid->bid_amount) : '—' }}</strong>
                </div>
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
                <p class="text-muted small mb-4">@lang('Closed'): {{ showDateTime($dispute->resolved_at) }}</p>
            @endif

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('user.disputes.index') }}" class="btn btn-outline--base btn-sm">@lang('All Disputes')</a>
                @if ($dispute->project_id)
                    <a href="{{ route('user.project.detail', $dispute->project_id) }}" class="btn btn--base btn-sm">
                        @lang('View Project')
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
