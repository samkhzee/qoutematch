@php
    $chatViewerRole = $chatViewerRole ?? 'buyer';
    $chatConversationId = (int) ($chatConversationId ?? 0);
    $chatPollUrl = $chatConversationId
        ? route($chatViewerRole === 'buyer' ? 'buyer.conversation.messages' : 'user.conversation.messages', $chatConversationId)
        : null;
    $chatFileBaseUrl = asset(getFilePath('message'));
    $chatCurrentUserId = $chatViewerRole === 'buyer'
        ? (int) auth()->guard('buyer')->id()
        : (int) auth()->id();
    $chatPeerUserId = $chatViewerRole === 'buyer'
        ? (int) (@$conversation->user_id ?? 0)
        : (int) (@$conversation->buyer_id ?? 0);
@endphp
<script>
(function ($) {
    "use strict";

    const chatViewerRole = @json($chatViewerRole);
    const chatPollUrl = @json($chatPollUrl);
    const chatFileBaseUrl = @json($chatFileBaseUrl);
    const chatCurrentUserId = {{ $chatCurrentUserId }};
    const knownMessageIds = new Set();

    $(document).ready(function () {
        scrollToBottom();
        $('.single-message[data-message-id]').each(function () {
            const messageId = parseInt($(this).attr('data-message-id'), 10);
            if (messageId) {
                knownMessageIds.add(messageId);
            }
        });
    });

    function scrollToBottom() {
        const chatBox = $(".chat-box__thread");
        if (chatBox.length > 0) {
            chatBox.scrollTop(chatBox[0].scrollHeight);
        }
    }

    const chatBoxThread = document.querySelector('.chat-box__thread');
    if (chatBoxThread) {
        const observer = new MutationObserver(function () {
            scrollToBottom();
        });
        observer.observe(chatBoxThread, { childList: true, subtree: true });
    }

    $('#messageForm').on('keypress', function (e) {
        if (e.which == 13 && !e.shiftKey) {
            e.preventDefault();
            messageSubmit();
        }
    });

    $('#messageForm').on('submit', function (e) {
        e.preventDefault();
        messageSubmit();
    });

    function messageSubmit() {
        const $form = $('#messageForm');
        const url = $form.data('store-url') || $form.attr('action');
        if (!url || url === '#') {
            notify('error', 'Select a conversation first.');
            return;
        }

        const messageText = $.trim($form.find('textarea[name="message"]').val() || '');
        if (!messageText) {
            notify('error', 'Message field is required');
            return;
        }

        const formData = new FormData($form[0]);
        const $submitBtn = $form.find('.chating-btn');
        $submitBtn.prop('disabled', true).addClass('is-sending');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            type: 'POST',
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 'success') {
                    $form[0].reset();
                    $('.files-here').removeClass('show');
                    $('.chat-box').removeClass('add-file');
                    $('.empty-message').remove();

                    if (response.data && response.data.message) {
                        appendChatMessage(response.data.message);
                    }
                } else {
                    const errorMessage = response.message?.error?.[0] || response.message?.error || 'Failed to send message';
                    notify('error', errorMessage);
                }
            },
            error: function (xhr) {
                const payload = xhr.responseJSON || {};
                const errorMessage = payload.message?.error?.[0]
                    || payload.message?.error
                    || payload.message
                    || 'Failed to send message';
                notify('error', errorMessage);
            },
            complete: function () {
                $submitBtn.prop('disabled', false).removeClass('is-sending');
            },
        });
    }

    $(".messageFileUpload").on('change', function () {
        if (this.files && this.files.length > 0) {
            $('.files-here').addClass('show');
            $('.chat-box').addClass('add-file');
            $('.files-here span b').text(this.files.length);
        }
    });

    $(".removeFile").on('click', function () {
        $(".chat-box").find('input[type=file]').val('');
        $('.files-here').removeClass('show');
        $('.chat-box').removeClass('add-file');
    });

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    function formatMessageHtml(value) {
        return escapeHtml(value).replace(/\n/g, '<br>');
    }

    function appendChatMessage(data) {
        if (data.id && knownMessageIds.has(data.id)) {
            return;
        }

        if (data.id) {
            knownMessageIds.add(data.id);
        }

        const styleClass = data.side ? `message--${data.side}` : (chatViewerRole === 'buyer'
            ? (data.buyerId == chatCurrentUserId ? 'message--right' : 'message--left')
            : (data.userId == chatCurrentUserId ? 'message--right' : 'message--left'));

        let fileLinks = '';
        if (data.files && data.files.length > 0) {
            data.files.forEach((file) => {
                fileLinks += `<a href="${chatFileBaseUrl}/${file}" download>
                    <i class="las la-file"></i> ${escapeHtml(file)}
                </a>`;
            });
        }

        const senderName = escapeHtml(data.senderName || (styleClass.includes('right') ? 'You' : 'User'));
        const messageTime = data.time || 'Just now';

        $('.chat-box__thread').append(`
            <div class="single-message ${styleClass}" ${data.id ? `data-message-id="${data.id}"` : ''}>
                <div class="message-content-outer">
                    <span class="message-sender">${senderName}</span>
                    <div class="message-content">
                        <p class="message-text">
                            ${data.action == 1 ? 'Action: ' : ''}
                            ${data.message ? formatMessageHtml(data.message) : ''}
                        </p>
                        ${fileLinks ? `<small class="message-box__text">${fileLinks}</small>` : ''}
                    </div>
                    <span class="message-time d-block mt-1">${messageTime}</span>
                </div>
            </div>
        `);

        scrollToBottom();
    }

    function liveChat(data) {
        if (data.action == 1) {
            pollMessages();
            return;
        }

        if (chatViewerRole === 'buyer' && data.buyerId == chatCurrentUserId && !data.userId) {
            return;
        }

        if (chatViewerRole === 'freelancer' && data.userId == chatCurrentUserId) {
            return;
        }

        if (!data.senderName) {
            pollMessages();
            return;
        }

        appendChatMessage(data);
    }

    @if (!empty(gs()->pusher_config->app_key))
    if (typeof Pusher !== 'undefined') {
        const pusher = new Pusher("{{ gs()->pusher_config->app_key }}", {
            cluster: "{{ gs()->pusher_config->cluster }}",
        });

        pusher.connection.bind('connected', () => {
            const SOCKET_ID = pusher.connection.socket_id;
            const BASE_URL = "{{ route('home') }}";
            const CHANNEL_NAME = `private-conversation_{{ $chatConversationId }}`;
            pusher.config.authEndpoint = `${BASE_URL}/pusher/auth/${SOCKET_ID}/${CHANNEL_NAME}`;
            const channel = pusher.subscribe(CHANNEL_NAME);
            channel.bind('pusher:subscription_succeeded', function () {
                channel.bind(`conversation_{{ $chatConversationId }}`, liveChat);
            });
        });
    }
    @endif

    function pollMessages() {
        if (!chatPollUrl) {
            return;
        }

        fetch(chatPollUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
            .then((response) => response.json())
            .then((response) => {
                if (response.status !== 'success' || !response.data?.messages) {
                    return;
                }

                response.data.messages.forEach(appendChatMessage);
            })
            .catch(() => {});
    }

    if (chatPollUrl) {
        pollMessages();
        setInterval(pollMessages, 3000);
    }

    window.appendChatMessage = appendChatMessage;
})(jQuery);
</script>
