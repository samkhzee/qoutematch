import { Link, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '@/Components/Layout/AdminLayout';
import Pagination from '@/Components/Shared/Pagination';

export default function Index({ pageTitle, categories }) {
    const rows = categories?.data ?? [];
    const [editing, setEditing] = useState(null);

    return (
        <AdminLayout pageTitle={pageTitle}>
            <div className="admin-categories">
            <div className="row gy-4">
                <div className="col-lg-4">
                    <CategoryForm
                        key={editing?.id ?? 'new'}
                        categories={categories}
                        editing={editing}
                        onCancel={() => setEditing(null)}
                    />
                </div>
                <div className="col-lg-8">
                    <div className="card shadow-sm">
                        <div className="admin-mobile-cards d-lg-none">
                            {rows.length === 0 ? (
                                <p className="text-center text-muted py-4 mb-0">No categories found.</p>
                            ) : rows.map((row) => (
                                <CategoryMobileCard key={row.id} row={row} onEdit={setEditing} />
                            ))}
                        </div>

                        <div className="table-responsive d-none d-lg-block">
                            <table className="table table--light style--two mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Request Form</th>
                                        <th>Quote Form</th>
                                        <th>Subcats</th>
                                        <th>Jobs</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {rows.length === 0 ? (
                                        <tr><td colSpan={7} className="text-center text-muted py-4">No categories found.</td></tr>
                                    ) : rows.map((row) => (
                                        <CategoryRow key={row.id} row={row} onEdit={setEditing} />
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {categories?.links?.length > 3 && (
                            <div className="card-footer"><Pagination links={categories.links} /></div>
                        )}
                    </div>
                </div>
            </div>
            </div>
        </AdminLayout>
    );
}

function CategoryMobileCard({ row, onEdit }) {
    const statusForm = useForm({});
    const featureForm = useForm({});

    return (
        <article className="admin-mobile-card">
            <div className="admin-mobile-card__head">
                <span className="avatar avatar--sm">
                    <img src={row.image} alt="" />
                </span>
                <div className="admin-mobile-card__title">{row.name}</div>
                <span className={row.status.class}>{row.status.label}</span>
            </div>

            <div className="admin-mobile-card__meta">
                <div className="admin-mobile-card__meta-item">
                    <span className="admin-mobile-card__meta-label">Request Form</span>
                    <span className="admin-mobile-card__meta-value">
                        {row.requestForm ? <code className="small">{row.requestForm}</code> : '—'}
                    </span>
                </div>
                <div className="admin-mobile-card__meta-item">
                    <span className="admin-mobile-card__meta-label">Quote Form</span>
                    <span className="admin-mobile-card__meta-value">
                        {row.quoteForm ? <code className="small">{row.quoteForm}</code> : '—'}
                    </span>
                </div>
                <div className="admin-mobile-card__meta-item">
                    <span className="admin-mobile-card__meta-label">Subcategories</span>
                    <span className="admin-mobile-card__meta-value">
                        <Link href={row.subcategoriesUrl} className="btn btn-sm btn-outline--primary admin-categories__btn admin-categories__btn--link">
                            {row.subcategoriesCount}
                        </Link>
                    </span>
                </div>
                <div className="admin-mobile-card__meta-item">
                    <span className="admin-mobile-card__meta-label">Jobs</span>
                    <span className="admin-mobile-card__meta-value">{row.jobsCount}</span>
                </div>
            </div>

            <CategoryActions row={row} onEdit={onEdit} statusForm={statusForm} featureForm={featureForm} />
        </article>
    );
}

function CategoryRow({ row, onEdit }) {
    const statusForm = useForm({});
    const featureForm = useForm({});

    return (
        <tr>
            <td>
                <div className="d-flex align-items-center gap-2">
                    <span className="avatar avatar--sm">
                        <img src={row.image} alt="" />
                    </span>
                    <span>{row.name}</span>
                </div>
            </td>
            <td className="small">
                {row.requestForm ? <code>{row.requestForm}</code> : '—'}
            </td>
            <td className="small">
                {row.quoteForm ? <code>{row.quoteForm}</code> : '—'}
            </td>
            <td>
                <Link href={row.subcategoriesUrl} className="btn btn-sm btn-outline--primary admin-categories__btn admin-categories__btn--link">{row.subcategoriesCount}</Link>
            </td>
            <td>{row.jobsCount}</td>
            <td><span className={row.status.class}>{row.status.label}</span></td>
            <td>
                <CategoryActions row={row} onEdit={onEdit} statusForm={statusForm} featureForm={featureForm} inline />
            </td>
        </tr>
    );
}

function CategoryActions({ row, onEdit, statusForm, featureForm, inline = false }) {
    const className = inline
        ? 'admin-categories__actions'
        : 'admin-mobile-card__actions admin-categories__actions';

    return (
        <div className={className}>
            <button type="button" className="btn btn-sm btn-outline--dark admin-categories__btn admin-categories__btn--dark" onClick={() => onEdit(row)}>Edit</button>
            <button type="button" className="btn btn-sm btn-outline--warning admin-categories__btn admin-categories__btn--toggle" disabled={statusForm.processing}
                onClick={() => statusForm.post(row.statusUrl)}>Toggle</button>
            <button type="button" className="btn btn-sm btn-outline--info admin-categories__btn admin-categories__btn--feature" disabled={featureForm.processing}
                onClick={() => featureForm.post(row.featureUrl)}>{row.isFeatured ? 'Unfeature' : 'Feature'}</button>
        </div>
    );
}

function CategoryForm({ categories, editing, onCancel }) {
    const form = useForm({
        name: editing?.name ?? '',
        request_form_id: '',
        quote_form_id: '',
        image: null,
    });

    const submit = (e) => {
        e.preventDefault();
        form.post(editing ? editing.storeUrl : categories.createUrl, {
            forceFormData: true,
            onSuccess: () => { form.reset(); onCancel?.(); },
        });
    };

    return (
        <div className="card shadow-sm">
            <div className="card-header bg-white"><h6 className="mb-0">{editing ? 'Edit Category' : 'Add Category'}</h6></div>
            <div className="card-body">
                <form onSubmit={submit}>
                    <div className="form-group mb-3">
                        <label>Name</label>
                        <input className="form-control" value={form.data.name} onChange={(e) => form.setData('name', e.target.value)} required />
                    </div>
                    <div className="form-group mb-3">
                        <label>Request Form</label>
                        <select className="form-control" value={form.data.request_form_id} onChange={(e) => form.setData('request_form_id', e.target.value)}>
                            <option value="">— None —</option>
                            {(categories.formOptions?.request ?? []).map((f) => <option key={f.id} value={f.id}>{f.act}</option>)}
                        </select>
                    </div>
                    <div className="form-group mb-3">
                        <label>Quote Form</label>
                        <select className="form-control" value={form.data.quote_form_id} onChange={(e) => form.setData('quote_form_id', e.target.value)}>
                            <option value="">— None —</option>
                            {(categories.formOptions?.quote ?? []).map((f) => <option key={f.id} value={f.id}>{f.act}</option>)}
                        </select>
                    </div>
                    <div className="form-group mb-3">
                        <label>Image{editing ? ' (optional)' : ''}</label>
                        <input type="file" className="form-control" accept="image/*" onChange={(e) => form.setData('image', e.target.files[0])} />
                    </div>
                    <div className="admin-categories__form-actions">
                        <button type="submit" className="btn btn--primary admin-categories__btn admin-categories__btn--save" disabled={form.processing}>Save</button>
                        {editing && <button type="button" className="btn btn-outline--dark admin-categories__btn admin-categories__btn--dark" onClick={onCancel}>Cancel</button>}
                    </div>
                </form>
            </div>
        </div>
    );
}
