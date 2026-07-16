import { useForm } from '@inertiajs/react';

export default function TwoFactorPanel({ twoFactor }) {
    const enableForm = useForm({ key: twoFactor.secret, code: '' });
    const disableForm = useForm({ code: '' });

    return (
        <div className="row justify-content-center gy-4">
            {!twoFactor.enabled && (
                <div className="col-xl-7">
                    <div className="card custom--card">
                        <div className="card-header"><h5 className="card-title mb-0">Add Your Account</h5></div>
                        <div className="card-body">
                            <p>Use the QR code or setup key on your Google Authenticator app.</p>
                            <img src={twoFactor.qrCodeUrl} alt="QR" className="mx-auto d-block mb-3" />
                            <div className="form-group">
                                <label className="form--label">Setup Key</label>
                                <input className="form-control form--control" value={twoFactor.secret} readOnly />
                            </div>
                        </div>
                    </div>
                </div>
            )}
            <div className="col-xl-5">
                <div className="card custom--card">
                    <div className="card-header">
                        <h5 className="card-title mb-0">{twoFactor.enabled ? 'Disable 2FA Security' : 'Enable 2FA Security'}</h5>
                    </div>
                    {twoFactor.enabled ? (
                        <form onSubmit={(e) => { e.preventDefault(); disableForm.post(twoFactor.disableUrl); }}>
                            <div className="card-body">
                                <div className="form-group">
                                    <label className="form--label">Google Authenticator OTP</label>
                                    <input className="form-control form--control" value={disableForm.data.code} onChange={(e) => disableForm.setData('code', e.target.value)} required />
                                </div>
                                <button type="submit" className="btn btn--base w-100" disabled={disableForm.processing}>Submit</button>
                            </div>
                        </form>
                    ) : (
                        <form onSubmit={(e) => { e.preventDefault(); enableForm.post(twoFactor.enableUrl); }}>
                            <div className="card-body">
                                <div className="form-group">
                                    <label className="form--label">Google Authenticator OTP</label>
                                    <input className="form-control form--control" value={enableForm.data.code} onChange={(e) => enableForm.setData('code', e.target.value)} required />
                                </div>
                                <button type="submit" className="btn btn--base w-100" disabled={enableForm.processing}>Submit</button>
                            </div>
                        </form>
                    )}
                </div>
            </div>
        </div>
    );
}
