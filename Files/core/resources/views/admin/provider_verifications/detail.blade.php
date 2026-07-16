@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h5 class="mb-1">{{ $verification->typeLabel() }}</h5>
                            <p class="mb-0 text-muted">
                                {{ $verification->user?->fullname }} ({{ $verification->user?->username }})
                            </p>
                        </div>
                        @if ($verification->status == Status::VERIFICATION_APPROVED)
                            <span class="badge badge--success">@lang('Approved')</span>
                        @elseif ($verification->status == Status::VERIFICATION_REJECTED)
                            <span class="badge badge--danger">@lang('Rejected')</span>
                        @else
                            <span class="badge badge--warning">@lang('Pending')</span>
                        @endif
                    </div>

                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Reference number')
                            <span>{{ $verification->reference_number ?: '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Expiry date')
                            <span>{{ $verification->expires_at ? showDateTime($verification->expires_at, 'd M, Y') : '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Submitted')
                            <span>{{ showDateTime($verification->created_at) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Document')
                            <span>
                                @if ($verification->document)
                                    <a href="{{ route('admin.download.attachment', encrypt(getFilePath('verify') . '/' . $verification->document)) }}"
                                        target="_blank">
                                        <i class="fa-regular fa-file"></i> @lang('View attachment')
                                    </a>
                                @else
                                    @lang('No file')
                                @endif
                            </span>
                        </li>
                    </ul>

                    @if ($verification->admin_note)
                        <div class="alert alert-warning">
                            <strong>@lang('Admin note'):</strong> {{ $verification->admin_note }}
                        </div>
                    @endif

                    @if ($verification->status == Status::VERIFICATION_PENDING)
                        <div class="d-flex flex-wrap justify-content-end gap-2">
                            <button type="button" class="btn btn-outline--danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="las la-ban"></i> @lang('Reject')
                            </button>
                            <button type="button" class="btn btn-outline--success confirmationBtn"
                                data-question="@lang('Approve this verification badge?')"
                                data-action="{{ route('admin.provider.verifications.approve', $verification->id) }}">
                                <i class="las la-check"></i> @lang('Approve')
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reject Verification')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.provider.verifications.reject', $verification->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Rejection reason')</label>
                            <textarea class="form-control" name="admin_note" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
