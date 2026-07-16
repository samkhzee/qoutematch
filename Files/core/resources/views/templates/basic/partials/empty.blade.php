<div class="empty-message text-center py-5">
    <span class="d-flex justify-content-center align-items-center empty-slip-message">
        <img src="{{ asset(activeTemplate(true) . 'images/empty.png') }}" alt="empty">
        <h6 class="text-muted">{{ __($message) }}</h6>
    </span>
</div>

@push('style')
    <style>
        .empty-slip-message {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #cfcfcf;
            font-size: 0.8754rem;
            font-family: inherit;
        }

        .empty-slip-message img {
            width: 75px !important;
            margin-bottom: 0.875rem;
        }
    </style>
@endpush
