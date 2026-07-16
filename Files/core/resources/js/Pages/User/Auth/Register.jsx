import { useForm, usePage } from '@inertiajs/react';
import AuthLayout, { AuthShell, AuthLogo, RegisterTypeSwitch } from '@/Components/Layout/AuthLayout';

export default function Register({ pageTitle, authContent, categoryOptions, registrationEnabled, requireAgree, policies }) {
    const { site, routes } = usePage().props;

    const { data, setData, post, processing, errors } = useForm({
        firstname: '',
        lastname: '',
        email: '',
        password: '',
        password_confirmation: '',
        business_name: '',
        company_number: '',
        mobile: '',
        subcategory_ids: [],
        service_areas: '',
        agree: false,
    });

    const toggleSubcategory = (id) => {
        const current = data.subcategory_ids || [];
        setData(
            'subcategory_ids',
            current.includes(id) ? current.filter((item) => item !== id) : [...current, id]
        );
    };

    const submit = (event) => {
        event.preventDefault();
        post(routes.userRegister);
    };

    return (
        <AuthLayout pageTitle={pageTitle}>
            <AuthShell
                left={{ shape: authContent.bannerShape, image: authContent.image }}
                right={
                    <>
                        <AuthLogo />
                        <form onSubmit={submit} className="registerForm">
                            <div className="account-form">
                                <RegisterTypeSwitch current="provider" />
                                <p className="text">Welcome to {site.name}</p>
                                <h5 className="account-form__title">{authContent.heading}</h5>

                                <div className={registrationEnabled ? '' : 'form-disabled p-3'}>
                                    <div className="row">
                                        <div className="col-sm-6 form-group">
                                            <label htmlFor="firstname" className="form--label">First Name</label>
                                            <input type="text" id="firstname" className="form-control form--control"
                                                value={data.firstname} onChange={(e) => setData('firstname', e.target.value)} required />
                                            {errors.firstname && <small className="text-danger">{errors.firstname}</small>}
                                        </div>
                                        <div className="col-sm-6 form-group">
                                            <label htmlFor="lastname" className="form--label">Last Name</label>
                                            <input type="text" id="lastname" className="form-control form--control"
                                                value={data.lastname} onChange={(e) => setData('lastname', e.target.value)} required />
                                            {errors.lastname && <small className="text-danger">{errors.lastname}</small>}
                                        </div>
                                        <div className="col-sm-12 form-group">
                                            <label htmlFor="business_name" className="form--label">Business Name</label>
                                            <input type="text" id="business_name" className="form-control form--control"
                                                value={data.business_name} onChange={(e) => setData('business_name', e.target.value)} required />
                                            {errors.business_name && <small className="text-danger">{errors.business_name}</small>}
                                        </div>
                                        <div className="col-sm-6 form-group">
                                            <label htmlFor="company_number" className="form--label">Company Number (optional)</label>
                                            <input type="text" id="company_number" className="form-control form--control"
                                                value={data.company_number} onChange={(e) => setData('company_number', e.target.value)} />
                                        </div>
                                        <div className="col-sm-6 form-group">
                                            <label htmlFor="mobile" className="form--label">Phone</label>
                                            <input type="text" id="mobile" className="form-control form--control"
                                                value={data.mobile} onChange={(e) => setData('mobile', e.target.value)} required />
                                            {errors.mobile && <small className="text-danger">{errors.mobile}</small>}
                                        </div>
                                        <div className="col-sm-12 form-group">
                                            <label htmlFor="email" className="form--label">Email Address</label>
                                            <input type="email" id="email" className="form-control form--control"
                                                value={data.email} onChange={(e) => setData('email', e.target.value)} required />
                                            {errors.email && <small className="text-danger">{errors.email}</small>}
                                        </div>
                                        <div className="col-sm-12 form-group">
                                            <label className="form--label">Categories You Cover</label>
                                            <div className="register-category-list">
                                                {categoryOptions.map((category) => (
                                                    <div key={category.id} className="register-category-group">
                                                        <strong>{category.name}</strong>
                                                        <div className="d-flex flex-wrap gap-2 mt-2">
                                                            {category.subcategories.map((sub) => (
                                                                <label key={sub.id} className="category-tag mb-0">
                                                                    <input
                                                                        type="checkbox"
                                                                        className="form-check-input me-1"
                                                                        checked={data.subcategory_ids.includes(sub.id)}
                                                                        onChange={() => toggleSubcategory(sub.id)}
                                                                    />
                                                                    {sub.name}
                                                                </label>
                                                            ))}
                                                        </div>
                                                    </div>
                                                ))}
                                            </div>
                                            {errors.subcategory_ids && <small className="text-danger d-block">{errors.subcategory_ids}</small>}
                                        </div>
                                        <div className="col-sm-12 form-group">
                                            <label htmlFor="service_areas" className="form--label">Service Areas / Postcodes</label>
                                            <textarea id="service_areas" className="form-control form--control" rows="2"
                                                placeholder="e.g. Manchester, M1-M20, Preston"
                                                value={data.service_areas} onChange={(e) => setData('service_areas', e.target.value)} required />
                                            {errors.service_areas && <small className="text-danger">{errors.service_areas}</small>}
                                        </div>
                                        <div className="col-sm-6 form-group">
                                            <label htmlFor="password" className="form--label">Password</label>
                                            <input id="password" type="password" className="form-control form--control"
                                                value={data.password} onChange={(e) => setData('password', e.target.value)} required />
                                            {errors.password && <small className="text-danger">{errors.password}</small>}
                                        </div>
                                        <div className="col-sm-6 form-group">
                                            <label htmlFor="password_confirmation" className="form--label">Confirm Password</label>
                                            <input id="password_confirmation" type="password" className="form-control form--control"
                                                value={data.password_confirmation} onChange={(e) => setData('password_confirmation', e.target.value)} required />
                                        </div>

                                        {requireAgree && (
                                            <div className="col-sm-12 form-group form--check mb-2 flex-nowrap">
                                                <input type="checkbox" className="form-check-input" id="agree"
                                                    checked={data.agree} onChange={(e) => setData('agree', e.target.checked)} required />
                                                <label htmlFor="agree" className="mx-2">
                                                    I agree with{' '}
                                                    {policies.map((policy, index) => (
                                                        <span key={policy.slug}>
                                                            <a href={policy.url} target="_blank" rel="noreferrer">{policy.title}</a>
                                                            {index < policies.length - 1 ? ', ' : ''}
                                                        </span>
                                                    ))}
                                                </label>
                                            </div>
                                        )}

                                        <div className="col-sm-12 form-group">
                                            <button type="submit" className="btn btn--base w-100" disabled={processing || !registrationEnabled}>
                                                Register as Provider
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <p className="account-form__text">
                                    Already have an account?{' '}
                                    <a href={routes.userLogin} className="text--base">Login Now</a>
                                </p>
                            </div>
                        </form>
                    </>
                }
            />
        </AuthLayout>
    );
}
