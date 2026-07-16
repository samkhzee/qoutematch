import { useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function BuyerProfileForm({ profile }) {
    const [preview, setPreview] = useState(profile.image);
    const form = useForm({
        firstname: profile.firstname ?? '',
        lastname: profile.lastname ?? '',
        address: profile.address ?? '',
        city: profile.city ?? '',
        state: profile.state ?? '',
        zip: profile.zip ?? '',
        language: profile.language ?? [],
        image: null,
    });

    const submit = (event) => {
        event.preventDefault();
        form.post(profile.submitUrl, { forceFormData: true });
    };

    const toggleLanguage = (lang) => {
        const current = form.data.language || [];
        form.setData(
            'language',
            current.includes(lang) ? current.filter((item) => item !== lang) : [...current, lang],
        );
    };

    const commonLanguages = ['English', 'Spanish', 'French', 'German', 'Arabic', 'Hindi', 'Chinese'];

    return (
        <div className="card custom--card">
            <div className="card-body">
                <form onSubmit={submit} encType="multipart/form-data">
                    <div className="row gy-4">
                        <div className="col-xl-4">
                            <div className="user-profile m-auto text-center">
                                <img src={preview} alt="" className="mb-3 rounded" style={{ maxWidth: '180px' }} />
                                <h5>{profile.fullname}</h5>
                                <label className="btn btn--base w-100 mt-3">
                                    Change Profile Photo
                                    <input
                                        type="file"
                                        hidden
                                        accept="image/*"
                                        onChange={(e) => {
                                            const file = e.target.files[0];
                                            if (file) {
                                                form.setData('image', file);
                                                setPreview(URL.createObjectURL(file));
                                            }
                                        }}
                                    />
                                </label>
                            </div>
                        </div>
                        <div className="col-xl-8">
                            <div className="row gy-3">
                                <div className="col-md-6">
                                    <label className="form--label">First Name</label>
                                    <input className="form-control form--control" value={form.data.firstname} onChange={(e) => form.setData('firstname', e.target.value)} required />
                                </div>
                                <div className="col-md-6">
                                    <label className="form--label">Last Name</label>
                                    <input className="form-control form--control" value={form.data.lastname} onChange={(e) => form.setData('lastname', e.target.value)} required />
                                </div>
                                <div className="col-md-12">
                                    <label className="form--label">Email Address</label>
                                    <input className="form-control form--control" value={profile.email} readOnly />
                                </div>
                                <div className="col-md-6">
                                    <label className="form--label">Mobile Number</label>
                                    <input className="form-control form--control" value={profile.mobile} readOnly />
                                </div>
                                <div className="col-md-6">
                                    <label className="form--label">Country</label>
                                    <input className="form-control form--control" value={profile.countryName} disabled />
                                </div>
                                {['address', 'city', 'state', 'zip'].map((field) => (
                                    <div className="col-md-6" key={field}>
                                        <label className="form--label">{field.charAt(0).toUpperCase() + field.slice(1)}</label>
                                        <input
                                            className="form-control form--control"
                                            value={form.data[field]}
                                            onChange={(e) => form.setData(field, e.target.value)}
                                        />
                                    </div>
                                ))}
                                <div className="col-12">
                                    <label className="form--label">Language</label>
                                    <div className="d-flex flex-wrap gap-2">
                                        {commonLanguages.map((lang) => (
                                            <label key={lang} className="form-check">
                                                <input
                                                    type="checkbox"
                                                    className="form-check-input"
                                                    checked={(form.data.language || []).includes(lang)}
                                                    onChange={() => toggleLanguage(lang)}
                                                />
                                                <span className="form-check-label">{lang}</span>
                                            </label>
                                        ))}
                                    </div>
                                </div>
                            </div>
                            <button type="submit" className="btn btn--base mt-3" disabled={form.processing}>Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    );
}
