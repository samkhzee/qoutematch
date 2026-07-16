import { useEffect, useRef, useState } from 'react';
import { notify } from '@/utils/helpers';

export default function useMessageNotifications(pollUrl, initialCount = 0) {
    const [unreadCount, setUnreadCount] = useState(initialCount);
    const lastCountRef = useRef(null);

    useEffect(() => {
        setUnreadCount(initialCount);
    }, [initialCount]);

    useEffect(() => {
        if (!pollUrl) {
            return undefined;
        }

        let cancelled = false;

        const poll = async () => {
            try {
                const response = await window.axios.get(pollUrl, {
                    headers: { Accept: 'application/json' },
                });
                const payload = response.data?.data || response.data;
                if (!payload || cancelled) {
                    return;
                }

                const count = Number(payload.count || 0);

                if (lastCountRef.current !== null && count > lastCountRef.current) {
                    const onActiveChat = document.getElementById('messageForm')?.dataset?.storeUrl;
                    if (!onActiveChat) {
                        const sender = payload.sender || 'Someone';
                        const preview = payload.preview || 'You have a new message';
                        notify('info', `New message from ${sender}: ${preview}`);
                    }
                }

                lastCountRef.current = count;
                setUnreadCount(count);
            } catch {
                // Ignore transient polling errors.
            }
        };

        poll();
        const timer = window.setInterval(poll, 5000);

        return () => {
            cancelled = true;
            clearInterval(timer);
        };
    }, [pollUrl]);

    return unreadCount;
}
