import { Link } from '@inertiajs/react';
import AdminLayout from '@/Components/Layout/AdminLayout';
import Pagination from '@/Components/Shared/Pagination';

export default function Index({ pageTitle, jobs }) {
    const rows = jobs?.data ?? [];

    return (
        <AdminLayout pageTitle={pageTitle}>
            <div className="card shadow-sm admin-jobs-card">
                <div className="admin-mobile-cards d-lg-none">
                    {rows.length === 0 ? (
                        <p className="text-center text-muted py-4 mb-0">No requests found.</p>
                    ) : rows.map((row) => (
                        <article key={row.id} className="admin-mobile-card admin-jobs-mobile-card">
                            <div className="admin-mobile-card__head">
                                <div className="admin-mobile-card__title">{row.title}</div>
                                <span className={row.approval.class}>{row.approval.label}</span>
                            </div>
                            <div className="admin-mobile-card__meta admin-mobile-card__meta--single">
                                <div className="admin-mobile-card__meta-item">
                                    <span className="admin-mobile-card__meta-label">Customer</span>
                                    <span className="admin-mobile-card__meta-value">{row.buyerUsername}</span>
                                </div>
                                <div className="admin-mobile-card__meta-item">
                                    <span className="admin-mobile-card__meta-label">Category</span>
                                    <span className="admin-mobile-card__meta-value">{row.category}</span>
                                </div>
                                <div className="admin-mobile-card__meta-item">
                                    <span className="admin-mobile-card__meta-label">Budget</span>
                                    <span className="admin-mobile-card__meta-value">{row.budget}</span>
                                </div>
                                <div className="admin-mobile-card__meta-item">
                                    <span className="admin-mobile-card__meta-label">Status</span>
                                    <span className="admin-mobile-card__meta-value"><span className={row.status.class}>{row.status.label}</span></span>
                                </div>
                                <div className="admin-mobile-card__meta-item">
                                    <span className="admin-mobile-card__meta-label">Date</span>
                                    <span className="admin-mobile-card__meta-value">{row.createdAt}</span>
                                </div>
                            </div>
                            <div className="admin-table-actions admin-table-actions--stack">
                                <Link href={row.detailUrl} className="btn btn-sm btn-outline--primary">Details</Link>
                                <Link href={row.bidsUrl} className="btn btn-sm btn-outline--dark">Quotes</Link>
                            </div>
                        </article>
                    ))}
                </div>

                <div className="table-responsive d-none d-lg-block">
                    <table className="table table--light style--two mb-0 admin-jobs-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Customer</th>
                                <th>Category</th>
                                <th>Budget</th>
                                <th>Status</th>
                                <th>Approval</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {rows.length === 0 ? (
                                <tr><td colSpan={8} className="text-center text-muted py-4">No requests found.</td></tr>
                            ) : rows.map((row) => (
                                <tr key={row.id}>
                                    <td className="admin-jobs-table__title">{row.title}</td>
                                    <td>{row.buyerUsername}</td>
                                    <td>{row.category}</td>
                                    <td className="admin-jobs-table__budget">{row.budget}</td>
                                    <td><span className={row.status.class}>{row.status.label}</span></td>
                                    <td><span className={row.approval.class}>{row.approval.label}</span></td>
                                    <td className="admin-jobs-table__date">{row.createdAt}</td>
                                    <td>
                                        <div className="admin-table-actions">
                                            <Link href={row.detailUrl} className="btn btn-sm btn-outline--primary">Details</Link>
                                            <Link href={row.bidsUrl} className="btn btn-sm btn-outline--dark">Quotes</Link>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {jobs?.links?.length > 3 && (
                    <div className="card-footer">
                        <Pagination links={jobs.links} />
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}
