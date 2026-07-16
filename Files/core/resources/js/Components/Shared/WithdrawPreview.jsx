import { useForm } from '@inertiajs/react';
import { useState } from 'react';
import RequestFormFields from '@/Components/Jobs/RequestFormFields';

export default function WithdrawPreview({ preview }) {
    const [fieldValues, setFieldValues] = useState({});
    const form = useForm({ authenticator_code: '' });

    const submit = (event) => {
        event.preventDefault();
        const data = new FormData();
        Object.entries(fieldValues).forEach(([key, value]) => {
            if (value instanceof File) {
                data.append(key, value);
            } else if (Array.isArray(value)) {
                value.forEach((item) => data.append(`${key}[]`, item));
            } else if (value !== null && value !== undefined) {
                data.append(key, value);
            }
        });
        if (preview.requires2fa) {
            data.append('authenticator_code', form.data.authenticator_code);
        }
        form.transform(() => Object.fromEntries(data.entries()));
        form.post(preview.submitUrl, { forceFormData: true });
    };

    return (
        <div className="card custom--card">
            <div className="card-body">
                <div className="alert alert-primary">
                    You are requesting <b>{preview.amount}</b> for withdraw. The admin will send you{' '}
                    <b className="text--success">{preview.finalAmount}</b> to your account.
                </div>
                {preview.description && (
                    <div className="mb-3" dangerouslySetInnerHTML={{ __html: preview.description }} />
                )}
                <form onSubmit={submit} encType="multipart/form-data">
                    <RequestFormFields
                        fields={preview.fields}
                        values={fieldValues}
                        onChange={(label, value) => setFieldValues((prev) => ({ ...prev, [label]: value }))}
                    />
                    {preview.requires2fa && (
                        <div className="form-group mt-3">
                            <label className="form--label">Google Authenticator Code</label>
                            <input
                                className="form-control form--control"
                                value={form.data.authenticator_code}
                                onChange={(e) => form.setData('authenticator_code', e.target.value)}
                                required
                            />
                        </div>
                    )}
                    <button type="submit" className="btn btn--base w-100 mt-3" disabled={form.processing}>Submit</button>
                </form>
            </div>
        </div>
    );
}
