import { useState } from 'react';

export default function PasswordInput({
    id = 'password',
    name = 'password',
    value,
    onChange,
    className = 'form-control form--control',
    required = true,
    autoComplete = 'current-password',
    placeholder,
}) {
    const [visible, setVisible] = useState(false);

    return (
        <div className="password-input-wrapper position-relative">
            <input
                id={id}
                name={name}
                type={visible ? 'text' : 'password'}
                className={`${className} pe-5`}
                value={value}
                onChange={onChange}
                required={required}
                autoComplete={autoComplete}
                placeholder={placeholder}
            />
            <button
                type="button"
                className="password-input-toggle btn btn-link p-0"
                onClick={() => setVisible((prev) => !prev)}
                aria-label={visible ? 'Hide password' : 'Show password'}
                tabIndex={-1}
            >
                <i className={`las ${visible ? 'la-eye-slash' : 'la-eye'}`} />
            </button>
        </div>
    );
}
