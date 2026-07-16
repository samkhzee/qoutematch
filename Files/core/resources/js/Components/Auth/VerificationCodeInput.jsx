import { useEffect, useRef } from 'react';

export default function VerificationCodeInput({ value, onChange, onComplete, error }) {
    const inputRef = useRef(null);

    useEffect(() => {
        if (value.length === 6 && onComplete && document.activeElement === inputRef.current) {
            onComplete(value);
        }
    }, [value, onComplete]);

    const digits = String(value ?? '').padEnd(6, '-').split('').slice(0, 6);

    return (
        <div className="mb-3">
            <label className="form-label">Verification Code</label>
            <div className="verification-code">
                <input
                    ref={inputRef}
                    type="text"
                    name="code"
                    className="form-control overflow-hidden"
                    value={value}
                    onChange={(e) => onChange(e.target.value.replace(/\s/g, '').slice(0, 6))}
                    required
                    autoComplete="off"
                    inputMode="numeric"
                />
                <div className="boxes">
                    {digits.map((digit, index) => (
                        <span key={index}>{digit === '-' ? '' : digit}</span>
                    ))}
                </div>
            </div>
            {error && <small className="text-danger d-block mt-2">{error}</small>}
        </div>
    );
}
