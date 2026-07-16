@extends('Template::layouts.buyer_master')
@section('content')
    <div class="dashboard-card">
        <div class="dashboard-card__header">
            <h6 class="dashboard-card__title mb-0">@lang('Notifications')</h6>
        </div>
        <div class="dashboard-card__body p-0">
            <div class="table-responsive">
                <table class="table table--responsive--md mb-0">
                    <thead>
                        <tr>
                            <th>@lang('Sent')</th>
                            <th>@lang('Channel')</th>
                            <th>@lang('Subject')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>
                                    {{ showDateTime($log->created_at) }}
                                    <br>
                                    <span class="text-muted small">{{ diffForHumans($log->created_at) }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ keyToTitle($log->notification_type) }}</span>
                                    <br>
                                    <span class="text-muted small">@lang('via') {{ __($log->sender) }}</span>
                                </td>
                                <td>{{ $log->subject ? __($log->subject) : __('N/A') }}</td>
                                <td>
                                    <button type="button" class="btn btn--base btn-sm notifyDetail"
                                        data-message="{{ $log->message }}"
                                        @if ($log->image) data-image="{{ asset(getFilePath('push') . '/' . $log->image) }}" @endif
                                        data-sent_to="{{ $log->sent_to }}">
                                        @lang('Details')
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center text-muted py-4">
                                    @lang('No notifications yet. Activity such as new quotes, messages, and disputes will appear here.')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($logs->hasPages())
            <div class="dashboard-card__footer">
                {{ paginateLinks($logs) }}
            </div>
        @endif
    </div>

    <div class="modal fade" id="notifyDetailModal" tabindex="-1" aria-labelledby="notifyDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notifyDetailModalLabel">@lang('Notification Details')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-center mb-3">@lang('To'): <span class="sent_to"></span></h6>
                    <div class="detail"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('.notifyDetail').on('click', function() {
            var message = '';
            if ($(this).data('image')) {
                message += `<img src="${$(this).data('image')}" class="w-100 mb-2" alt="image">`;
            }
            message += $(this).data('message');
            $('.detail').html(message);
            $('.sent_to').text($(this).data('sent_to'));
            $('#notifyDetailModal').modal('show');
        });
    </script>
@endpush
