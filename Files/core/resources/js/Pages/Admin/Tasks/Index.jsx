import { Link } from '@inertiajs/react';
import AdminLayout from '@/Components/Layout/AdminLayout';
import Pagination from '@/Components/Shared/Pagination';

export default function Index({ pageTitle, tasks }) {
    const rows = tasks?.data ?? [];

    return (
        <AdminLayout pageTitle={pageTitle}>
            <div className="card shadow-sm">
                <div className="table-responsive">
                    <table className="table table--light mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Request</th>
                                <th>Customer</th>
                                <th>Provider</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {rows.length === 0 ? (
                                <tr><td colSpan={7} className="text-center text-muted py-4">No trial tasks found.</td></tr>
                            ) : rows.map((row) => (
                                <tr key={row.id}>
                                    <td>{row.title}</td>
                                    <td>{row.jobTitle}</td>
                                    <td>{row.buyerUsername}</td>
                                    <td>{row.providerUsername}</td>
                                    <td><span className={row.status.class}>{row.status.label}</span></td>
                                    <td>{row.createdAt}</td>
                                    <td><Link href={row.detailUrl} className="btn btn-sm btn-outline--primary">Details</Link></td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                {tasks?.links?.length > 3 && (
                    <div className="card-footer"><Pagination links={tasks.links} /></div>
                )}
            </div>
        </AdminLayout>
    );
}
