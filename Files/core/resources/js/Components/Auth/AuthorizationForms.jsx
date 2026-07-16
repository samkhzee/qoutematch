import { router, useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import VerificationCodeInput from '@/Components/Auth/VerificationCodeInput';

export function ResetCodeVerifyForm({ email, maskedEmail, submitUrl, resendUrl, devResetCode, localMailInboxUrl }) {
    const initialCode = devResetCode != null ? String(devResetCode) : '';
    const form = useForm({ code: initialCode, email });

    const submit = (event) => {
        event.preventDefault();
        form.post(submitUrl);
    };

    return (
        <div className="verification-code-wrapper">
            <div className="verification-area">
                <form onSubmit={submit}>
                    <p className="verification-text">
                        A 6 digit verification code was sent to: {maskedEmail}
                    </p>
                    {devResetCode && (
                        <div className="alert alert-success mb-3 text-center">
                            <div className="mb-1"><strong>Your verification code</strong></div>
                            <div style={{ fontSize: '2rem', letterSpacing: '0.35em', fontWeight: 700 }}>{String(devResetCode)}</div>
                            {localMailInboxUrl && (
                                <small className="d-block mt-2 text-muted">
                                    On local Laragon, emails do not go to Gmail. They are captured in{' '}
                                    <a href={localMailInboxUrl} target="_blank" rel="noreferrer">Mailpit</a>.
                                </small>
                            )}
                        </div>
                    )}
                    <VerificationCodeInput
                        value={form.data.code}
                        onChange={(code) => form.setData('code', code)}
                        onComplete={() => form.post(submitUrl)}
                        error={form.errors.code}
                    />
                    <button type="submit" className="btn btn--base w-100" disabled={form.processing}>
                        Submit
                    </button>
                    <p className="mt-3 mb-0">
                        Check your Junk/Spam folder. If not found,{' '}
                        <a href={resendUrl}>try again</a>.
                    </p>
                </form>
            </div>
        </div>
    );
}

export function AuthorizationPanel({ authz }) {
    const form = useForm({ code: '' });
    const [countdown, setCountdown] = useState(authz.countdownSeconds ?? 0);

    useEffect(() => {
        if (countdown <= 0) {
            return undefined;
        }
        const timer = setInterval(() => setCountdown((value) => Math.max(0, value - 1)), 1000);
        return () => clearInterval(timer);
    }, [countdown]);

    if (authz.type === 'ban') {
        return (
            <div className="text-center ban-wrapper">
                <a href={authz.homeUrl} className="ban-wrapper__logo">
                    <img src={window.location.origin + '/assets/images/logoIcon/logo.png'} alt="" onError={(e) => { e.target.style.display = 'none'; }} />
                </a>
                {authz.banImage && <img className="ban-wrapper-image" src={authz.banImage} alt="" />}
                <h3 className="ban-wrapper-title">{authz.banHeading}</h3>
                <p className="ban-wrapper-desc">{authz.banReason}</p>
                <a className="btn btn--xl btn--base" href={authz.homeUrl}>Go to Home</a>
            </div>
        );
    }

    const contactText = authz.type === 'sms'
        ? authz.maskedMobile
        : authz.maskedEmail;

    const submit = (event) => {
        event.preventDefault();
        form.post(authz.submitUrl);
    };

    return (
        <div className="verification-code-wrapper">
            <div className="verification-area">
                <h5 className="pb-3 text-center border-bottom">{authz.pageTitle}</h5>
                <form onSubmit={submit}>
                    <p className="verification-text">
                        A 6 digit verification code was sent to: {contactText}
                    </p>
                    <VerificationCodeInput
                        value={form.data.code}
                        onChange={(code) => form.setData('code', code)}
                        error={form.errors.code}
                    />
                    <button type="submit" className="btn btn--base w-100" disabled={form.processing}>
                        Submit
                    </button>
                    {authz.resendUrl && (
                        <p className="mt-3 mb-0">
                            {countdown > 0 ? (
                                <>Try again after <strong>{countdown}</strong> seconds</>
                            ) : (
                                <a
                                    href="#"
                                    onClick={(e) => {
                                        e.preventDefault();
                                        router.get(authz.resendUrl);
                                    }}
                                >
                                    Try again
                                </a>
                            )}
                        </p>
                    )}
                    <a className="btn btn-outline--danger btn--sm mt-3" href={authz.logoutUrl}>Logout</a>
                </form>
            </div>
        </div>
    );
}
