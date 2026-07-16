import { Link } from '@inertiajs/react';
import Pagination from '@/Components/Shared/Pagination';
import StatusBadge from '@/Components/Shared/StatusBadge';

export default function DisputeList({ disputes, emptyMessage }) {
    const rows = disputes?.data ?? [];

    return (
        <div className="card custom--card">
            <div className="card-header">
                <h5 className="card-title mb-0">Disputes</h5>
            </div>
            <div className="card-body p-0">
                <div className="table-responsive">
                    <table className="table table--responsive--md mb-0">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Request</th>
                                <th>Provider</th>
                                <th>Type</th>
                                <th>Raised By</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {rows.length === 0 ? (
                                <tr>
                                    <td colSpan={8} className="text-center text-muted py-4">
                                        {emptyMessage || 'No disputes yet.'}
                                    </td>
                                </tr>
                            ) : (
                                rows.map((dispute) => (
                                    <tr key={dispute.id}>
                                        <td>{dispute.subject}</td>
                                        <td>{dispute.jobTitle}</td>
                                        <td>{dispute.providerName}</td>
                                        <td>{dispute.typeLabel}</td>
                                        <td>{dispute.raisedBy}</td>
                                        <td>{dispute.createdAt}</td>
                                        <td><StatusBadge status={dispute.status} /></td>
                                        <td>
                                            <Link href={dispute.detailUrl} className="btn btn--base btn-sm">Details</Link>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
            {disputes?.links?.length > 3 && (
                <div className="card-footer">
                    <Pagination links={disputes.links} />
                </div>
            )}
        </div>
    );
}
