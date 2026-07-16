import { useForm } from '@inertiajs/react';
import PasswordInput from '@/Components/Shared/PasswordInput';

export default function ResetPasswordForm({ email, token, submitUrl, securePassword }) {
    const form = useForm({
        email: email ?? '',
        token: token != null ? String(token) : '',
        password: '',
        password_confirmation: '',
    });

    const submit = (event) => {
        event.preventDefault();
        form.post(submitUrl);
    };

    return (
        <div className="card custom--card">
            <div className="card-body">
                <p className="mb-4">
                    Your account is verified. Choose a strong password and do not share it with anyone.
                </p>
                <form onSubmit={submit}>
                    <div className="form-group mb-3">
                        <label className="form--label">Password</label>
                        <PasswordInput
                            value={form.data.password}
                            onChange={(e) => form.setData('password', e.target.value)}
                            className={securePassword ? 'secure-password' : ''}
                            required
                        />
                        {form.errors.password && <small className="text-danger">{form.errors.password}</small>}
                    </div>
                    <div className="form-group mb-3">
                        <label className="form--label">Confirm Password</label>
                        <PasswordInput
                            value={form.data.password_confirmation}
                            onChange={(e) => form.setData('password_confirmation', e.target.value)}
                            required
                        />
                    </div>
                    <button type="submit" className="btn btn--base w-100" disabled={form.processing}>
                        Submit
                    </button>
                </form>
            </div>
        </div>
    );
}
