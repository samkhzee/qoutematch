import AppLayout from '@/Components/Layout/AppLayout';
import { Link, usePage } from '@inertiajs/react';

export default function AuthLayout({ children, pageTitle }) {
    return (
        <AppLayout pageTitle={pageTitle}>
            <section className="account">{children}</section>
        </AppLayout>
    );
}

export function AuthShell({ left, right }) {
    return (
        <div className="account-inner">
            <div className="account-inner__left">
                <div className="account-inner__shape">
                    <img src={left.shape} alt="" />
                </div>
                <div className="account-thumb">
                    <img src={left.image} alt="" />
                </div>
            </div>
            <div className="account-inner__right">
                <div className="account-form-wrapper">{right}</div>
            </div>
        </div>
    );
}

export function AuthLogo() {
    const { site, routes } = usePage().props;
    return (
        <Link href={routes.home} className="account-form__logo">
            <img src={site.logo} alt="" />
        </Link>
    );
}

export function RegisterTypeSwitch({ current = 'provider' }) {
    const { routes, authContent } = usePage().props;

    return (
        <div className="radio-btn-wrapper">
            <div className="form--radio">
                <input
                    className="form-check-input"
                    type="radio"
                    name="join-wrapper"
                    id="join-provider"
                    checked={current === 'provider'}
                    onChange={() => { window.location.href = routes.userRegister; }}
                />
                <label className="form-check-label" htmlFor="join-provider">
                    <span className="text">{authContent?.providerLabel || 'Join as Provider'}</span>
                </label>
            </div>
            <div className="form--radio">
                <input
                    className="form-check-input"
                    type="radio"
                    name="join-wrapper"
                    id="join-customer"
                    checked={current === 'customer'}
                    onChange={() => { window.location.href = routes.buyerRegister; }}
                />
                <label className="form-check-label" htmlFor="join-customer">
                    <span className="text">{authContent?.customerLabel || 'Join as Customer'}</span>
                </label>
            </div>
        </div>
    );
}

export function UserTypeSwitch({ current = 'provider' }) {
    const { routes } = usePage().props;

    return (
        <div className="radio-btn-wrapper">
            <div className="form--radio">
                <input
                    className="form-check-input"
                    type="radio"
                    name="apply-wrapper"
                    id="apply-provider"
                    checked={current === 'provider' || current === 'freelancer'}
                    onChange={() => { window.location.href = routes.userLogin; }}
                />
                <label className="form-check-label" htmlFor="apply-provider">
                    <span className="text">Provider Login</span>
                </label>
            </div>
            <div className="form--radio">
                <input
                    className="form-check-input"
                    type="radio"
                    name="apply-wrapper"
                    id="apply-customer"
                    checked={current === 'customer' || current === 'buyer'}
                    onChange={() => { window.location.href = routes.buyerLogin; }}
                />
                <label className="form-check-label" htmlFor="apply-customer">
                    <span className="text">Customer Login</span>
                </label>
            </div>
        </div>
    );
}
