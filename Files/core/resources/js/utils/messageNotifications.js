let pollTimer = null;
let lastCount = null;
let activePollUrl = null;

function renderHeaderBadge(count) {
    const notifyLink = document.querySelector('[data-message-notify-link]');
    if (!notifyLink) {
        return;
    }

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

function renderSidebarBadge(count) {
    const sidebarBadge = document.querySelector('[data-sidebar-chat-notify]');
    if (!sidebarBadge) {
        return;
    }

    if (count > 0) {
        sidebarBadge.classList.remove('d-none');
        sidebarBadge.innerHTML = `<i class="las la-bell"></i><span class="sidebar-chat-notify__count">${count > 9 ? '9+' : count}</span>`;
    } else {
        sidebarBadge.classList.add('d-none');
        sidebarBadge.innerHTML = '';
    }
}

function updateBadges(count) {
    renderHeaderBadge(count);
    renderSidebarBadge(count);
}

function showMessageToast(data) {
    if (typeof window.notify !== 'function') {
        return;
    }

    const onActiveChat = document.getElementById('messageForm')?.dataset?.storeUrl;
    if (onActiveChat) {
        return;
    }

    const sender = data.sender || 'Someone';
    const preview = data.preview || 'You have a new message';
    window.notify('info', `New message from ${sender}: ${preview}`);
}

async function pollUnreadSummary() {
    if (!activePollUrl) {
        return;
    }

    try {
        const response = await fetch(activePollUrl, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });

        const payload = await response.json();
        if (payload.status !== 'success' || !payload.data) {
            return;
        }

        const count = Number(payload.data.count || 0);

        if (lastCount !== null && count > lastCount) {
            showMessageToast(payload.data);
        }

        lastCount = count;
        updateBadges(count);
    } catch {
        // Ignore transient network errors during polling.
    }
}

export function initMessageNotifications(pollUrl) {
    if (!pollUrl) {
        return;
    }

    if (activePollUrl === pollUrl && pollTimer) {
        return;
    }

    if (pollTimer) {
        clearInterval(pollTimer);
    }

    activePollUrl = pollUrl;
    lastCount = null;
    pollUnreadSummary();
    pollTimer = window.setInterval(pollUnreadSummary, 5000);
}

export function stopMessageNotifications() {
    if (pollTimer) {
        clearInterval(pollTimer);
    }

    pollTimer = null;
    activePollUrl = null;
    lastCount = null;
}
