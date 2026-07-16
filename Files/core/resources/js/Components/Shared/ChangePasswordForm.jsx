import { useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function ChangePasswordForm({ submitUrl }) {
    const form = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });
    const [show, setShow] = useState({ current: false, next: false, confirm: false });

    const submit = (event) => {
        event.preventDefault();
        form.post(submitUrl);
    };

    return (
        <div className="card custom--card">
            <div className="card-body">
                <form onSubmit={submit}>
                    {[
                        { key: 'current_password', label: 'Current Password', showKey: 'current' },
                        { key: 'password', label: 'New Password', showKey: 'next' },
                        { key: 'password_confirmation', label: 'Confirm Password', showKey: 'confirm' },
                    ].map((field) => (
                        <div className="form-group mb-3" key={field.key}>
                            <label className="form--label">{field.label}</label>
                            <div className="input-group">
                                <input
                                    type={show[field.showKey] ? 'text' : 'password'}
                                    className="form-control form--control"
                                    value={form.data[field.key]}
                                    onChange={(e) => form.setData(field.key, e.target.value)}
                                    required
                                />
                                <button
                                    type="button"
                                    className="input-group-text"
                                    onClick={() => setShow((s) => ({ ...s, [field.showKey]: !s[field.showKey] }))}
                                >
                                    <i className={`las ${show[field.showKey] ? 'la-eye-slash' : 'la-eye'}`} />
                                </button>
                            </div>
                        </div>
                    ))}
                    <button type="submit" className="btn btn--base" disabled={form.processing}>Change Password</button>
                </form>
            </div>
        </div>
    );
}
