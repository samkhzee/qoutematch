import { Link } from '@inertiajs/react';
import AdminLayout from '@/Components/Layout/AdminLayout';
import Pagination from '@/Components/Shared/Pagination';

export default function Index({ pageTitle, users }) {
    const rows = users?.data ?? [];

    return (
        <AdminLayout pageTitle={pageTitle}>
            <div className="card shadow-sm">
                <div className="table-responsive">
                    <table className="table table--light mb-0">
                        <thead>
                            <tr>
                                <th>Provider</th>
                                <th>Email</th>
                                <th>Balance</th>
                                <th>Approved</th>
                                <th>Profile</th>
                                <th>Joined</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {rows.length === 0 ? (
                                <tr><td colSpan={7} className="text-center text-muted py-4">No providers found.</td></tr>
                            ) : rows.map((row) => (
                                <tr key={row.id}>
                                    <td>
                                        <Link href={row.detailUrl}>{row.fullname}</Link>
                                        <div className="small text-muted">@{row.username}</div>
                                    </td>
                                    <td>{row.email}</td>
                                    <td>{row.balance}</td>
                                    <td>
                                        <span className={row.providerApproved ? 'badge badge--success' : 'badge badge--warning'}>
                                            {row.providerApproved ? 'Yes' : 'No'}
                                        </span>
                                    </td>
                                    <td>
                                        <span className={row.profileComplete ? 'badge badge--success' : 'badge badge--dark'}>
                                            {row.profileComplete ? 'Complete' : 'Incomplete'}
                                        </span>
                                    </td>
                                    <td>{row.joinedAt}</td>
                                    <td>
                                        <Link href={row.detailUrl} className="btn btn-sm btn-outline--primary">Details</Link>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                {users?.links?.length > 3 && (
                    <div className="card-footer"><Pagination links={users.links} /></div>
                )}
            </div>
        </AdminLayout>
    );
}
