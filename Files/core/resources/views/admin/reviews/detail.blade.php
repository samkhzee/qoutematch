@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h5 class="mb-1">@lang('Customer review for') {{ $review->user?->fullname }}</h5>
                            <p class="mb-0 text-muted">
                                {{ $review->project?->job?->title ?? __('Project') }} · {{ showDateTime($review->created_at) }}
                            </p>
                        </div>
                        @if ($review->status == Status::REVIEW_APPROVED)
                            <span class="badge badge--success">@lang('Approved')</span>
                        @elseif ($review->status == Status::REVIEW_HIDDEN)
                            <span class="badge badge--danger">@lang('Hidden')</span>
                        @else
                            <span class="badge badge--warning">@lang('Pending')</span>
                        @endif
                    </div>

                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Customer')
                            <span>{{ $review->buyer?->fullname ?? '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Overall rating')
                            <span>{{ $review->rating }}/5</span>
                        </li>
                    </ul>

                    <div class="mb-4">
                        <h6 class="mb-3">@lang('Structured scores')</h6>
                        @include('Template::partials.structured_review_scores', ['scores' => $review->scores ?? []])
                    </div>

                    <div class="mb-4">
                        <h6 class="mb-2">@lang('Written review')</h6>
                        <p class="mb-0">{{ $review->review }}</p>
                    </div>

                    @if ($review->admin_note)
                        <div class="alert alert-warning">
                            <strong>@lang('Admin note'):</strong> {{ $review->admin_note }}
                        </div>
                    @endif

                    @if ($review->status != Status::REVIEW_APPROVED)
                        <div class="d-flex flex-wrap justify-content-end gap-2 mb-2">
                            <button type="button" class="btn btn-outline--success confirmationBtn"
                                data-question="@lang('Approve and publish this review?')"
                                data-action="{{ route('admin.reviews.approve', $review->id) }}">
                                <i class="las la-check"></i> @lang('Approve')
                            </button>
                        </div>
                    @endif

                    @if ($review->status != Status::REVIEW_HIDDEN)
                        <div class="d-flex flex-wrap justify-content-end gap-2">
                            <button type="button" class="btn btn-outline--danger" data-bs-toggle="modal" data-bs-target="#hideReviewModal">
                                <i class="las la-eye-slash"></i> @lang('Hide')
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="hideReviewModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Hide Review')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.reviews.hide', $review->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Internal note (optional)')</label>
                            <textarea class="form-control" name="admin_note" rows="4" placeholder="@lang('Reason for hiding this review')"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Hide review')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
