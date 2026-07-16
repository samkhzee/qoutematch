import { Link } from '@inertiajs/react';
import AdminLayout from '@/Components/Layout/AdminLayout';
import Pagination from '@/Components/Shared/Pagination';

export default function Index({ pageTitle, bids, jobId = 0 }) {
    const rows = bids?.data ?? [];

    return (
        <AdminLayout pageTitle={pageTitle}>
            {jobId > 0 && (
                <div className="mb-3">
                    <Link href="/admin/bids/index" className="btn btn-sm btn-outline--dark">← All quotes</Link>
                </div>
            )}

            <div className="card shadow-sm">
                <div className="table-responsive">
                    <table className="table table--light mb-0">
                        <thead>
                            <tr>
                                <th>Request</th>
                                <th>Provider</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>ETA</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {rows.length === 0 ? (
                                <tr><td colSpan={8} className="text-center text-muted py-4">No quotes found.</td></tr>
                            ) : rows.map((row) => (
                                <tr key={row.id}>
                                    <td>
                                        {row.jobDetailUrl ? (
                                            <Link href={row.jobDetailUrl}>{row.jobTitle}</Link>
                                        ) : row.jobTitle}
                                    </td>
                                    <td>{row.providerUsername}</td>
                                    <td>{row.buyerUsername}</td>
                                    <td>{row.amount}</td>
                                    <td>{row.estimatedTime ?? '—'}</td>
                                    <td><span className={row.status.class}>{row.status.label}</span></td>
                                    <td>{row.createdAt}</td>
                                    <td>
                                        <Link href={row.detailUrl} className="btn btn-sm btn-outline--primary">Details</Link>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                {bids?.links?.length > 3 && (
                    <div className="card-footer">
                        <Pagination links={bids.links} />
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}
