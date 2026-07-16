@if ($dispute ?? null)
    <div class="card border-warning shadow-sm mt-4">
        <div class="card-header bg-opacity-10 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h6 class="mb-0 fw-semibold text--warning">
                <i class="las la-exclamation-triangle"></i> @lang('Dispute')
            </h6>
            @php echo $dispute->statusBadge @endphp
        </div>
        <div class="card-body">
            <p class="mb-2"><strong>{{ $dispute->subject }}</strong></p>
            <p class="mb-2 text-muted small">
                @lang('Type'): {{ $dispute->typeLabel }} · @lang('Raised by'): {{ ucfirst($dispute->raised_by) }}
            </p>
            <p class="mb-3">{{ strLimit($dispute->description, 200) }}</p>
            @if ($dispute->admin_note && in_array((int) $dispute->status, [App\Constants\Status::DISPUTE_RESOLVED, App\Constants\Status::DISPUTE_REJECTED], true))
                <div class="alert alert--info mb-3">
                    <strong>@lang('Admin response'):</strong> {{ $dispute->admin_note }}
                </div>
            @endif
            <a href="{{ $disputeDetailRoute }}" class="btn btn--base btn-sm">
                @lang('View Dispute Details')
            </a>
        </div>
    </div>
@endif
