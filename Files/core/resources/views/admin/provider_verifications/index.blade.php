@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-3">
        <div class="col-12">
            @if (request()->filled('user_id'))
                <div class="alert alert-info py-2 mb-3">
                    @lang('Showing verification submissions for user ID') #{{ request()->integer('user_id') }}.
                    <a href="{{ route('admin.provider.verifications.index', ['status' => $status]) }}" class="alert-link">@lang('Clear filter')</a>
                </div>
            @endif
            <div class="btn-group">
                <a href="{{ route('admin.provider.verifications.index', array_filter(['status' => 'pending', 'user_id' => request('user_id')])) }}"
                    class="btn btn-sm {{ $status === 'pending' ? 'btn--primary' : 'btn-outline--primary' }}">@lang('Pending')</a>
                <a href="{{ route('admin.provider.verifications.index', array_filter(['status' => 'approved', 'user_id' => request('user_id')])) }}"
                    class="btn btn-sm {{ $status === 'approved' ? 'btn--primary' : 'btn-outline--primary' }}">@lang('Approved')</a>
                <a href="{{ route('admin.provider.verifications.index', array_filter(['status' => 'rejected', 'user_id' => request('user_id')])) }}"
                    class="btn btn-sm {{ $status === 'rejected' ? 'btn--primary' : 'btn-outline--primary' }}">@lang('Rejected')</a>
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
                                    <th>@lang('Provider')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Reference')</th>
                                    <th>@lang('Expires')</th>
                                    <th>@lang('Submitted')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($verifications as $verification)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $verification->user?->fullname }}</span><br>
                                            <span class="small">@ {{ $verification->user?->username }}</span>
                                        </td>
                                        <td>{{ $verification->typeLabel() }}</td>
                                        <td>{{ $verification->reference_number ?: '—' }}</td>
                                        <td>{{ $verification->expires_at ? showDateTime($verification->expires_at, 'd M, Y') : '—' }}</td>
                                        <td>{{ showDateTime($verification->created_at) }}</td>
                                        <td>
                                            @if ($verification->status == Status::VERIFICATION_APPROVED)
                                                <span class="badge badge--success">@lang('Approved')</span>
                                            @elseif ($verification->status == Status::VERIFICATION_REJECTED)
                                                <span class="badge badge--danger">@lang('Rejected')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.provider.verifications.detail', $verification->id) }}"
                                                class="btn btn-sm btn-outline--primary">
                                                <i class="las la-desktop"></i> @lang('Review')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">@lang('No verification submissions found.')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($verifications->hasPages())
                    <div class="card-footer">
                        {{ paginateLinks($verifications) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
