import { Head, Link, router, useForm } from '@inertiajs/react';
import PasswordInput from '@/Components/Shared/PasswordInput';

export default function Login({ pageTitle, captchaHtml }) {
    const { data, setData, processing, errors } = useForm({
        username: '',
        password: '',
        remember: false,
        captcha: '',
    });

    const submit = (event) => {
        event.preventDefault();
        router.post('/admin', {
            username: data.username,
            password: data.password,
            remember: data.remember,
            captcha: data.captcha,
            captcha_secret: document.querySelector('[name=captcha_secret]')?.value ?? '',
        });
    };

    return (
        <>
            <Head title={pageTitle}>
                <link rel="stylesheet" href="/assets/global/css/bootstrap.min.css" />
                <link rel="stylesheet" href="/assets/global/css/all.min.css" />
                <link rel="stylesheet" href="/assets/global/css/line-awesome.min.css" />
                <link rel="stylesheet" href="/assets/admin/css/app.css" />
            </Head>
            <div
                className="login-main admin-panel"
                style={{ backgroundImage: "url('/assets/admin/images/login.jpg')" }}
            >
                <div className="container custom-container">
                    <div className="row justify-content-center">
                        <div className="col-xxl-5 col-xl-5 col-lg-6 col-md-8 col-sm-11">
                            <div className="login-area">
                                <div className="login-wrapper">
                                    <div className="login-wrapper__top">
                                        <h3 className="title text-white">
                                            Welcome to <strong>QuoteMatch</strong>
                                        </h3>
                                        <p className="text-white">Admin Login Dashboard</p>
                                    </div>
                                    <div className="login-wrapper__body">
                                        <form onSubmit={submit} className="cmn-form mt-30 login-form">
                                            <div className="form-group">
                                                <label>Username or Email</label>
                                                <input
                                                    type="text"
                                                    className="form-control"
                                                    value={data.username}
                                                    onChange={(e) => setData('username', e.target.value)}
                                                    required
                                                />
                                                {(errors.username || errors.email) && (
                                                    <small className="text-danger">
                                                        {errors.username || errors.email}
                                                    </small>
                                                )}
                                            </div>
                                            <div className="form-group">
                                                <div className="d-flex justify-content-between align-items-center mb-2">
                                                    <label className="mb-0">Password</label>
                                                    <Link href="/admin/password/reset" className="forget-text">
                                                        Forgot Password?
                                                    </Link>
                                                </div>
                                                <PasswordInput
                                                    className="form-control"
                                                    value={data.password}
                                                    onChange={(e) => setData('password', e.target.value)}
                                                />
                                                {errors.password && <small className="text-danger">{errors.password}</small>}
                                            </div>

                                            <div className="form-group">
                                                <div className="form-check">
                                                    <input
                                                        className="form-check-input"
                                                        type="checkbox"
                                                        id="remember"
                                                        checked={data.remember}
                                                        onChange={(e) => setData('remember', e.target.checked)}
                                                    />
                                                    <label className="form-check-label" htmlFor="remember">
                                                        Remember Me
                                                    </label>
                                                </div>
                                            </div>

                                            {captchaHtml && (
                                                <div className="form-group">
                                                    <div
                                                        className="mb-2"
                                                        dangerouslySetInnerHTML={{ __html: captchaHtml }}
                                                        onChange={(e) => {
                                                            const secret = e.target.form?.querySelector('[name=captcha_secret]')?.value;
                                                            if (secret) setData('captcha_secret', secret);
                                                        }}
                                                    />
                                                    <label className="form-label">Captcha</label>
                                                    <input
                                                        type="text"
                                                        className="form-control"
                                                        value={data.captcha}
                                                        onChange={(e) => {
                                                            setData('captcha', e.target.value);
                                                            const secretInput = document.querySelector('[name=captcha_secret]');
                                                            if (secretInput) setData('captcha_secret', secretInput.value);
                                                        }}
                                                        required
                                                    />
                                                </div>
                                            )}

                                            <button type="submit" className="btn cmn-btn w-100" disabled={processing}>
                                                {processing ? 'Logging in…' : 'LOGIN'}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
