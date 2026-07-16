@push('script')
<script>
(function () {
    "use strict";

    const pollUrl = @json($pollUrl ?? null);
    if (!pollUrl) {
        return;
    }

    let lastCount = null;

    function updateBadges(count) {
        const notifyLink = document.querySelector('[data-message-notify-link]');
        if (notifyLink) {
            let badge = notifyLink.querySelector('.notification-number');
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'notification-number';
                    notifyLink.appendChild(badge);
                }
                badge.textContent = count > 9 ? '9+' : String(count);
            } else if (badge) {
                badge.remove();
            }
        }

        const sidebarBadge = document.querySelector('[data-sidebar-chat-notify]');
        if (!sidebarBadge) {
            return;
        }

        if (count > 0) {
            sidebarBadge.classList.remove('d-none');
            sidebarBadge.innerHTML = '<i class="las la-bell"></i><span class="sidebar-chat-notify__count">' + (count > 9 ? '9+' : count) + '</span>';
        } else {
            sidebarBadge.classList.add('d-none');
            sidebarBadge.innerHTML = '';
        }
    }

    function showToast(data) {
        if (typeof notify !== 'function') {
            return;
        }

        const onActiveChat = document.getElementById('messageForm')?.dataset?.storeUrl;
        if (onActiveChat) {
            return;
        }

        const sender = data.sender || 'Someone';
        const preview = data.preview || 'You have a new message';
        notify('info', 'New message from ' + sender + ': ' + preview);
    }

    function pollUnreadSummary() {
        fetch(pollUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
            .then(function (response) { return response.json(); })
            .then(function (payload) {
                if (payload.status !== 'success' || !payload.data) {
                    return;
                }

                const count = Number(payload.data.count || 0);
                if (lastCount !== null && count > lastCount) {
                    showToast(payload.data);
                }

                lastCount = count;
                updateBadges(count);
            })
            .catch(function () {});
    }

    pollUnreadSummary();
    window.setInterval(pollUnreadSummary, 5000);
})();
</script>
@endpush
