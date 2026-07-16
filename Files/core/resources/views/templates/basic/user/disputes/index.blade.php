@extends('Template::layouts.master')
@section('content')
    <div class="dashboard-card">
        <div class="dashboard-card__header">
            <h6 class="dashboard-card__title mb-0">@lang('Disputes')</h6>
        </div>
        <div class="dashboard-card__body p-0">
            <div class="table-responsive">
                <table class="table table--responsive--md mb-0">
                    <thead>
                        <tr>
                            <th>@lang('Subject')</th>
                            <th>@lang('Request')</th>
                            <th>@lang('Customer')</th>
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
                                <td>{{ $dispute->buyer?->fullname ?? '—' }}</td>
                                <td>{{ $dispute->typeLabel }}</td>
                                <td>{{ ucfirst($dispute->raised_by) }}</td>
                                <td>{{ showDateTime($dispute->created_at) }}</td>
                                <td>@php echo $dispute->statusBadge @endphp</td>
                                <td>
                                    <a href="{{ route('user.disputes.detail', $dispute->id) }}" class="btn btn--base btn-sm">
                                        @lang('Details')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center text-muted py-4">
                                    @lang('No disputes yet. You can open a dispute by reporting a project from My Projects.')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($disputes->hasPages())
            <div class="dashboard-card__footer">
                {{ paginateLinks($disputes) }}
            </div>
        @endif
    </div>
@endsection
