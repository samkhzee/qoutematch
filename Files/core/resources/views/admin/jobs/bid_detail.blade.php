@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1">{{ __($bid->job?->title ?? 'Quote') }}</h5>
                        <span class="text-muted small">@lang('Submitted') {{ showDateTime($bid->created_at) }}</span>
                    </div>
                    @php echo $bid->statusBadge @endphp
                </div>
                <div class="card-body">
                    <div class="row gy-3 mb-4">
                        <div class="col-md-4">
                            <span class="text-muted d-block">@lang('Provider')</span>
                            <a href="{{ route('admin.users.detail', $bid->user_id) }}">{{ $bid->user?->fullname }}
                                (<span>@</span>{{ $bid->user?->username }})</a>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block">@lang('Customer')</span>
                            <a href="{{ route('admin.buyers.detail', $bid->buyer_id) }}">{{ $bid->buyer?->fullname }}
                                (<span>@</span>{{ $bid->buyer?->username }})</a>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block">@lang('Quote Amount')</span>
                            <strong>{{ showAmount($bid->bid_amount) }}</strong>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block">@lang('Estimate Time')</span>
                            <strong>{{ $bid->estimated_time ?? '—' }}</strong>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block">@lang('Request')</span>
                            @if ($bid->job)
                                <a href="{{ route('admin.jobs.details', $bid->job_id) }}">@lang('View request')</a>
                            @else
                                —
                            @endif
                        </div>
                        @if ($bid->project_id)
                            <div class="col-md-4">
                                <span class="text-muted d-block">@lang('Project')</span>
                                <a href="{{ route('admin.project.details', $bid->project_id) }}">@lang('View project')</a>
                            </div>
                        @endif
                    </div>

                    @if ($bid->proposal)
                        <h6 class="mb-2">@lang('Proposal')</h6>
                        <div class="border rounded p-3 mb-4">
                            @php echo $bid->proposal @endphp
                        </div>
                    @endif

                    @include('Template::buyer.job.partials.request_data_summary', [
                        'requestFields' => $requestFields ?? [],
                    ])

                    @if (!empty($quoteFields))
                        <div class="request-data-summary mt-4">
                            <h6 class="mb-3">@lang('Structured Quote Details')</h6>
                            <div class="row gy-3">
                                @foreach ($quoteFields as $field)
                                    <div class="col-md-6">
                                        <div class="request-data-item">
                                            <span class="request-data-item__label">{{ __($field['name']) }}</span>
                                            @if ($field['isFile'])
                                                <a href="{{ $field['value'] }}" class="request-data-item__value" target="_blank">
                                                    <i class="las la-download"></i> @lang('Download file')
                                                </a>
                                            @else
                                                <span class="request-data-item__value">{{ __($field['value']) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($bid->revision_note)
                        <div class="alert alert-warning mt-4">
                            <strong>@lang('Revision requested'):</strong> {{ $bid->revision_note }}
                            @if ($bid->revision_requested_at)
                                <span class="d-block small">{{ showDateTime($bid->revision_requested_at) }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Actions')</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.bids.index') }}" class="btn btn-outline--primary btn-sm">@lang('All Quotes')</a>
                    @if ($bid->job_id)
                        <a href="{{ route('admin.bids.index', $bid->job_id) }}" class="btn btn-outline--primary btn-sm">@lang('Quotes for this request')</a>
                    @endif
                    @if ((int) $bid->status !== Status::BID_ACCEPTED)
                        <button type="button" class="btn btn-outline--danger btn-sm confirmationBtn"
                            data-action="{{ route('admin.bids.delete', $bid->id) }}"
                            data-question="@lang('Are you sure to permanently delete this quote?')">
                            <i class="las la-trash"></i> @lang('Delete Quote')
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
