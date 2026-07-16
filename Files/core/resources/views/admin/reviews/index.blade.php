@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-3">
        <div class="col-12">
            @if (request()->filled('user_id'))
                <div class="alert alert-info py-2 mb-3">
                    @lang('Showing reviews for provider ID') #{{ request()->integer('user_id') }}.
                    <a href="{{ route('admin.reviews.index', ['status' => $status]) }}" class="alert-link">@lang('Clear filter')</a>
                </div>
            @endif
            <div class="btn-group">
                <a href="{{ route('admin.reviews.index', array_filter(['status' => 'pending', 'user_id' => request('user_id')])) }}"
                    class="btn btn-sm {{ $status === 'pending' ? 'btn--primary' : 'btn-outline--primary' }}">@lang('Pending')</a>
                <a href="{{ route('admin.reviews.index', array_filter(['status' => 'approved', 'user_id' => request('user_id')])) }}"
                    class="btn btn-sm {{ $status === 'approved' ? 'btn--primary' : 'btn-outline--primary' }}">@lang('Approved')</a>
                <a href="{{ route('admin.reviews.index', array_filter(['status' => 'hidden', 'user_id' => request('user_id')])) }}"
                    class="btn btn-sm {{ $status === 'hidden' ? 'btn--primary' : 'btn-outline--primary' }}">@lang('Hidden')</a>
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
                                    <th>@lang('Customer')</th>
                                    <th>@lang('Project')</th>
                                    <th>@lang('Overall')</th>
                                    <th>@lang('Submitted')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $review->user?->fullname }}</span><br>
                                            <span class="small">@ {{ $review->user?->username }}</span>
                                        </td>
                                        <td>{{ $review->buyer?->fullname ?? '—' }}</td>
                                        <td>{{ strLimit($review->project?->job?->title ?? '—', 40) }}</td>
                                        <td>{{ $review->rating }}/5</td>
                                        <td>{{ showDateTime($review->created_at) }}</td>
                                        <td>
                                            @if ($review->status == Status::REVIEW_APPROVED)
                                                <span class="badge badge--success">@lang('Approved')</span>
                                            @elseif ($review->status == Status::REVIEW_HIDDEN)
                                                <span class="badge badge--danger">@lang('Hidden')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.reviews.detail', $review->id) }}"
                                                class="btn btn-sm btn-outline--primary">
                                                <i class="las la-desktop"></i> @lang('Review')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">@lang('No reviews found.')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($reviews->hasPages())
                    <div class="card-footer">
                        {{ paginateLinks($reviews) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
