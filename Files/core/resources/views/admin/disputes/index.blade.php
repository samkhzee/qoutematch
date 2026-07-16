@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group flex-wrap">
                @foreach (['active' => 'Active', 'open' => 'Open', 'in_review' => 'In Review', 'resolved' => 'Resolved', 'rejected' => 'Rejected'] as $key => $label)
                    <a href="{{ route('admin.disputes.index', ['status' => $key]) }}"
                        class="btn btn-sm {{ $status === $key ? 'btn--primary' : 'btn-outline--primary' }} mb-1">
                        @lang($label)
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Request')</th>
                                    <th>@lang('Customer')</th>
                                    <th>@lang('Provider')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Raised By')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($disputes as $dispute)
                                    <tr>
                                        <td>{{ strLimit($dispute->subject, 45) }}</td>
                                        <td>{{ strLimit($dispute->job?->title ?? '—', 30) }}</td>
                                        <td>{{ $dispute->buyer?->username ?? '—' }}</td>
                                        <td>{{ $dispute->user?->username ?? '—' }}</td>
                                        <td>{{ $dispute->typeLabel }}</td>
                                        <td>{{ ucfirst($dispute->raised_by) }}</td>
                                        <td>{{ showDateTime($dispute->created_at) }}</td>
                                        <td>@php echo $dispute->statusBadge @endphp</td>
                                        <td>
                                            <a href="{{ route('admin.disputes.detail', $dispute->id) }}"
                                                class="btn btn-sm btn-outline--primary">
                                                <i class="las la-desktop"></i> @lang('Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">@lang('No disputes found.')</td>
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
        </div>
    </div>
@endsection
