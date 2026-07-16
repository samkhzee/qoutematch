import { Link, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '@/Components/Layout/AdminLayout';
import Pagination from '@/Components/Shared/Pagination';

export default function Subcategories({ pageTitle, subcategories }) {
    const rows = subcategories?.data ?? [];
    const [editing, setEditing] = useState(null);

    return (
        <AdminLayout pageTitle={pageTitle}>
            <div className="admin-categories">
            <div className="mb-3">
                <Link href="/admin/category/index" className="btn btn-sm btn-outline--dark admin-categories__btn admin-categories__btn--dark">← Categories</Link>
            </div>
            <div className="row gy-4">
                <div className="col-lg-4">
                    <SubcategoryForm subcategories={subcategories} editing={editing} onCancel={() => setEditing(null)} />
                </div>
                <div className="col-lg-8">
                    <div className="card shadow-sm">
                        <div className="admin-mobile-cards d-lg-none">
                            {rows.length === 0 ? (
                                <p className="text-center text-muted py-4 mb-0">No subcategories found.</p>
                            ) : rows.map((row) => (
                                <SubcategoryMobileCard key={row.id} row={row} onEdit={setEditing} />
                            ))}
                        </div>

                        <div className="table-responsive d-none d-lg-block">
                            <table className="table table--light style--two mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Jobs</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {rows.length === 0 ? (
                                        <tr><td colSpan={5} className="text-center text-muted py-4">No subcategories found.</td></tr>
                                    ) : rows.map((row) => (
                                        <SubcategoryRow key={row.id} row={row} onEdit={setEditing} />
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {subcategories?.links?.length > 3 && (
                            <div className="card-footer"><Pagination links={subcategories.links} /></div>
                        )}
                    </div>
                </div>
            </div>
            </div>
        </AdminLayout>
    );
}

function SubcategoryMobileCard({ row, onEdit }) {
    const statusForm = useForm({});

    return (
        <article className="admin-mobile-card">
            <div className="admin-mobile-card__head">
                <div className="admin-mobile-card__title">{row.name}</div>
                <span className={row.status.class}>{row.status.label}</span>
            </div>
            <div className="admin-mobile-card__meta admin-mobile-card__meta--single">
                <div className="admin-mobile-card__meta-item">
                    <span className="admin-mobile-card__meta-label">Category</span>
                    <span className="admin-mobile-card__meta-value">{row.category}</span>
                </div>
                <div className="admin-mobile-card__meta-item">
                    <span className="admin-mobile-card__meta-label">Jobs</span>
                    <span className="admin-mobile-card__meta-value">{row.jobsCount}</span>
                </div>
            </div>
            <div className="admin-mobile-card__actions admin-categories__actions">
                <button type="button" className="btn btn-sm btn-outline--dark admin-categories__btn admin-categories__btn--dark" onClick={() => onEdit(row)}>Edit</button>
                <button type="button" className="btn btn-sm btn-outline--warning admin-categories__btn admin-categories__btn--toggle" disabled={statusForm.processing}
                    onClick={() => statusForm.post(row.statusUrl)}>Toggle</button>
            </div>
        </article>
    );
}

function SubcategoryRow({ row, onEdit }) {
    const statusForm = useForm({});
    return (
        <tr>
            <td>{row.name}</td>
            <td>{row.category}</td>
            <td>{row.jobsCount}</td>
            <td><span className={row.status.class}>{row.status.label}</span></td>
            <td>
                <div className="admin-categories__actions">
                    <button type="button" className="btn btn-sm btn-outline--dark admin-categories__btn admin-categories__btn--dark" onClick={() => onEdit(row)}>Edit</button>
                    <button type="button" className="btn btn-sm btn-outline--warning admin-categories__btn admin-categories__btn--toggle" disabled={statusForm.processing}
                        onClick={() => statusForm.post(row.statusUrl)}>Toggle</button>
                </div>
            </td>
        </tr>
    );
}

function SubcategoryForm({ subcategories, editing, onCancel }) {
    const form = useForm({
        name: editing?.name ?? '',
        category_id: editing?.categoryId ? String(editing.categoryId) : '',
    });

    const submit = (e) => {
        e.preventDefault();
        form.post(editing ? editing.storeUrl : subcategories.createUrl, {
            onSuccess: () => { form.reset(); onCancel?.(); },
        });
    };

    return (
        <div className="card shadow-sm">
            <div className="card-header bg-white"><h6 className="mb-0">{editing ? 'Edit Subcategory' : 'Add Subcategory'}</h6></div>
            <div className="card-body">
                <form onSubmit={submit}>
                    <div className="form-group mb-3">
                        <label>Category</label>
                        <select className="form-control" value={form.data.category_id} onChange={(e) => form.setData('category_id', e.target.value)} required>
                            <option value="">Select category</option>
                            {(subcategories.categories ?? []).map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
                        </select>
                    </div>
                    <div className="form-group mb-3">
                        <label>Name</label>
                        <input className="form-control" value={form.data.name} onChange={(e) => form.setData('name', e.target.value)} required />
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
