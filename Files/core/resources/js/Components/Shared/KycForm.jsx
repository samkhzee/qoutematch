import { useForm } from '@inertiajs/react';
import { useState } from 'react';
import RequestFormFields from '@/Components/Jobs/RequestFormFields';

export default function KycForm({ fields, submitUrl, emptyMessage }) {
    const [fieldValues, setFieldValues] = useState({});
    const form = useForm({});

    if (!fields.length) {
        return (
            <div className="alert alert-warning mb-0">
                {emptyMessage || 'KYC verification is not configured yet. Please contact support.'}
            </div>
        );
    }

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
        form.transform(() => Object.fromEntries(data.entries()));
        form.post(submitUrl, { forceFormData: true });
    };

    return (
        <form onSubmit={submit} encType="multipart/form-data">
            <RequestFormFields
                fields={fields}
                values={fieldValues}
                onChange={(label, value) => setFieldValues((prev) => ({ ...prev, [label]: value }))}
            />
            <button type="submit" className="btn btn--base w-100 mt-3" disabled={form.processing}>Submit</button>
        </form>
    );
}
