import { Link } from '@inertiajs/react';
import AdminLayout from '@/Components/Layout/AdminLayout';
import Pagination from '@/Components/Shared/Pagination';

const STATUS_TABS = [
    { key: 'pending', label: 'Pending' },
    { key: 'approved', label: 'Approved' },
    { key: 'hidden', label: 'Hidden' },
];

export default function Index({ pageTitle, reviews }) {
    const rows = reviews?.data ?? [];

    return (
        <AdminLayout pageTitle={pageTitle}>
            <div className="btn-group flex-wrap mb-3">
                {STATUS_TABS.map((tab) => (
                    <Link
                        key={tab.key}
                        href={`/admin/reviews?status=${tab.key}`}
                        className={`btn btn-sm ${reviews.status === tab.key ? 'btn--primary' : 'btn-outline--primary'} mb-1`}
                    >
                        {tab.label}
                    </Link>
                ))}
            </div>

            <div className="card shadow-sm">
                <div className="table-responsive">
                    <table className="table table--light mb-0">
                        <thead>
                            <tr>
                                <th>Rating</th>
                                <th>Provider</th>
                                <th>Customer</th>
                                <th>Request</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {rows.length === 0 ? (
                                <tr><td colSpan={7} className="text-center text-muted py-4">No reviews found.</td></tr>
                            ) : rows.map((row) => (
                                <tr key={row.id}>
                                    <td>{row.rating}/5</td>
                                    <td>{row.providerUsername}</td>
                                    <td>{row.buyerUsername}</td>
                                    <td>{row.jobTitle}</td>
                                    <td>{row.createdAt}</td>
                                    <td><span className={row.status.class}>{row.status.label}</span></td>
                                    <td>
                                        <Link href={row.detailUrl} className="btn btn-sm btn-outline--primary">Details</Link>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                {reviews?.links?.length > 3 && (
                    <div className="card-footer">
                        <Pagination links={reviews.links} />
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}
