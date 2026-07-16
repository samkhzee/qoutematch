@extends('Template::layouts.buyer_master')
@section('content')
    <div class="card custom--card">
        <div class="card-header">
            <h5 class="card-title mb-0">@lang('Disputes')</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table--responsive--md mb-0">
                    <thead>
                        <tr>
                            <th>@lang('Subject')</th>
                            <th>@lang('Request')</th>
                            <th>@lang('Provider')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Raised By')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($disputes as $dispute)
                            <tr>
                                <td>{{ strLimit($dispute->subject, 40) }}</td>
                                <td>{{ strLimit($dispute->job?->title ?? '—', 30) }}</td>
                                <td>{{ $dispute->user?->fullname ?? '—' }}</td>
                                <td>{{ $dispute->typeLabel }}</td>
                                <td>{{ ucfirst($dispute->raised_by) }}</td>
                                <td>{{ showDateTime($dispute->created_at) }}</td>
                                <td>@php echo $dispute->statusBadge @endphp</td>
                                <td>
                                    <a href="{{ route('buyer.disputes.detail', $dispute->id) }}" class="btn btn--base btn-sm">
                                        @lang('Details')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center text-muted py-4">
                                    @lang('No disputes yet. You can open a dispute by reporting a project from My Projects while it is under review.')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($disputes->hasPages())
            <div class="card-footer">
                {{ paginateLinks($disputes) }}
            </div>
        @endif
    </div>
@endsection
