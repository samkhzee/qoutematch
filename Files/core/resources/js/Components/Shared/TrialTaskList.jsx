import Pagination from '@/Components/Shared/Pagination';
import StatusBadge from '@/Components/Shared/StatusBadge';

export default function TrialTaskList({ tasks, role }) {
    const rows = tasks?.data ?? [];

    return (
        <div className="table-wrapper">
            <table className="table table--responsive--md">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Amount</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        {role === 'buyer' ? <th>Provider</th> : <th>Customer</th>}
                        <th>Request</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {rows.length === 0 ? (
                        <tr><td colSpan={7} className="text-center text-muted py-4">No trial tasks found.</td></tr>
                    ) : (
                        rows.map((row) => (
                            <tr key={row.id}>
                                <td>{row.title}</td>
                                <td>{row.amount}</td>
                                <td>{row.deadline}</td>
                                <td><StatusBadge status={row.status} /></td>
                                <td>{role === 'buyer' ? row.provider : row.buyer}</td>
                                <td>{row.jobTitle}</td>
                                <td>
                                    {role === 'buyer' ? (
                                        <a href={row.detailUrl} className="btn btn--sm btn--base">View</a>
                                    ) : (
                                        <>
                                            {row.canUpload && (
                                                <a href={row.uploadUrl} className="btn btn--sm btn--base me-1">Upload</a>
                                            )}
                                            <a href={row.acceptUrl} className="btn btn--sm btn-outline--base">Accept</a>
                                        </>
                                    )}
                                </td>
                            </tr>
                        ))
                    )}
                </tbody>
            </table>
            {tasks?.links?.length > 3 && <Pagination links={tasks.links} />}
        </div>
    );
}
