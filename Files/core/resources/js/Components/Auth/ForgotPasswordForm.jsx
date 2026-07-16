import { useForm } from '@inertiajs/react';
import CaptchaField from '@/Components/Auth/CaptchaField';

export default function ForgotPasswordForm({ submitUrl, loginUrl, captchaHtml }) {
    const form = useForm({ value: '', captcha: '', captcha_secret: '' });

    const submit = (event) => {
        event.preventDefault();
        form.post(submitUrl);
    };

    return (
        <div className="card custom--card">
            <div className="card-body">
                <p className="mb-4">To recover your account, enter your email or username.</p>
                <form onSubmit={submit}>
                    <div className="form-group mb-3">
                        <label className="form-label">Email or Username</label>
                        <input
                            className="form-control form--control"
                            value={form.data.value}
                            onChange={(e) => form.setData('value', e.target.value)}
                            required
                            autoFocus
                        />
                        {form.errors.value && <small className="text-danger">{form.errors.value}</small>}
                    </div>
                    <CaptchaField
                        captchaHtml={captchaHtml}
                        data={form.data}
                        setData={form.setData}
                        error={form.errors.captcha}
                    />
                    <button type="submit" className="btn btn--base w-100" disabled={form.processing}>
                        Submit
                    </button>
                </form>
                <p className="mt-3 mb-0 text-center">
                    <a href={loginUrl}>Back to login</a>
                </p>
            </div>
        </div>
    );
}
