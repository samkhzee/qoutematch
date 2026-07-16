@php
    $isBlocked = (bool) ($conv->status ?? false);
@endphp
<div class="conversation-actions">
    <button type="button" class="conversation-actions__toggle" aria-label="@lang('Chat options')">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>
    <ul class="conversation-actions__menu">
        @if ($role === 'buyer')
            @if ($isBlocked)
                <li>
                    <button type="button" class="conversation-actions__item conversation-actions__item--success confirmationBtn"
                        data-question="@lang('Unblock this provider? They will be able to message you again.')"
                        data-action="{{ route('buyer.conversation.block', $conv->id) }}">
                        <i class="las la-unlock"></i> @lang('Unblock')
                    </button>
                </li>
            @else
                <li>
                    <button type="button" class="conversation-actions__item conversation-actions__item--danger confirmationBtn"
                        data-question="@lang('Block this provider? They will not be able to send you new messages.')"
                        data-action="{{ route('buyer.conversation.block', $conv->id) }}">
                        <i class="las la-ban"></i> @lang('Block')
                    </button>
                </li>
            @endif
        @endif
        <li>
            <button type="button" class="conversation-actions__item conversation-actions__item--muted confirmationBtn"
                data-question="@lang('Delete this chat from your inbox? The other person will still see the conversation.')"
                data-action="{{ $deleteRoute }}">
                <i class="las la-trash"></i> @lang('Delete chat')
            </button>
        </li>
    </ul>
</div>
