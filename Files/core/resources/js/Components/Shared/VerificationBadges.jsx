import { useEffect, useRef } from 'react';

export default function VerificationBadges({ badges = [], className = '', compact = false }) {
    const containerRef = useRef(null);

    useEffect(() => {
        if (!containerRef.current || typeof bootstrap === 'undefined' || !bootstrap.Tooltip) {
            return undefined;
        }

        const triggers = containerRef.current.querySelectorAll('[data-bs-toggle="tooltip"]');
        const instances = [...triggers].map((element) => {
            bootstrap.Tooltip.getInstance(element)?.dispose();
            return new bootstrap.Tooltip(element);
        });

        return () => {
            instances.forEach((instance) => instance.dispose());
        };
    }, [badges, compact]);

    if (!badges.length) {
        return null;
    }

    return (
        <span ref={containerRef} className={`verification-badges ${className}`.trim()}>
            {badges.map((badge) => (
                <span
                    key={badge.key}
                    className={`verification-badge verification-badge--${badge.tone || badge.key}${compact ? ' verification-badge--compact' : ''}`}
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title={badge.label}
                    aria-label={badge.label}
                >
                    <i className={badge.icon} aria-hidden="true"></i>
                    {!compact && <span>{badge.label}</span>}
                </span>
            ))}
        </span>
    );
}
