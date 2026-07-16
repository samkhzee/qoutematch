import { useForm } from '@inertiajs/react';

export default function CaptchaField({ captchaHtml, data, setData, error }) {
    if (!captchaHtml) {
        return null;
    }

    return (
        <div className="form-group">
            <div
                className="mb-2"
                dangerouslySetInnerHTML={{ __html: captchaHtml }}
                onChange={(e) => {
                    const secret = e.target.form?.querySelector('[name=captcha_secret]')?.value;
                    if (secret) {
                        setData('captcha_secret', secret);
                    }
                }}
            />
            <label className="form-label">Captcha</label>
            <input
                className="form-control form--control"
                value={data.captcha ?? ''}
                onChange={(e) => {
                    setData('captcha', e.target.value);
                    const secretInput = document.querySelector('[name=captcha_secret]');
                    if (secretInput) {
                        setData('captcha_secret', secretInput.value);
                    }
                }}
                required
            />
            {error && <small className="text-danger">{error}</small>}
        </div>
    );
}

export function useCaptchaForm(initial = {}) {
    return useForm({
        ...initial,
        captcha: '',
        captcha_secret: '',
    });
}
