import { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';

const COOKIE_NAME = 'gdpr_cookie';
const COOKIE_MAX_AGE = 60 * 60 * 24 * 365;

function readCookie(name) {
    const match = document.cookie.match(new RegExp(`(?:^|; )${name}=([^;]*)`));
    return match ? decodeURIComponent(match[1]) : null;
}

function writeCookie(value) {
    document.cookie = `${COOKIE_NAME}=${encodeURIComponent(value)};path=/;max-age=${COOKIE_MAX_AGE};SameSite=Lax`;
}

export default function CookieBanner() {
    const { routes, cookieConsent, cookieSettings } = usePage().props;
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        if (!cookieSettings?.enabled) {
            setVisible(false);
            return undefined;
        }

        const existing = cookieConsent || readCookie(COOKIE_NAME);
        if (!existing) {
            const timer = window.setTimeout(() => setVisible(true), 800);
            return () => window.clearTimeout(timer);
        }
        setVisible(false);
        return undefined;
    }, [cookieConsent, cookieSettings?.enabled]);

    const saveChoice = async (action) => {
        writeCookie(action);
        setVisible(false);

        try {
            await window.axios.get(routes.cookieAccept, { params: { action } });
        } catch {
            // Local cookie already set; banner stays dismissed.
        }
    };

    if (!visible) return null;

    return (
        <div className="cookies-card text-center">
            <div className="cookies-card__icon bg--base">
                <i className="las la-cookie-bite"></i>
            </div>
            <p className="cookies-card__desc">
                {cookieSettings?.shortDesc || 'We use cookies to improve your experience.'}{' '}
                <a className="text--base" href={routes.cookiePolicy} target="_blank" rel="noreferrer">
                    learn more
                </a>
            </p>
            <div className="cookies-card__btn">
                <button type="button" className="btn btn--base btn--sm" onClick={() => saveChoice('accepted')}>
                    Allow
                </button>
                <button type="button" className="btn btn-outline--secondary btn--sm" onClick={() => saveChoice('rejected')}>
                    Reject
                </button>
            </div>
        </div>
    );
}
