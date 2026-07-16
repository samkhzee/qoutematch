import { Link } from '@inertiajs/react';
import Pagination from '@/Components/Shared/Pagination';
import StatusBadge from '@/Components/Shared/StatusBadge';

export default function SupportTicketList({ tickets, openUrl }) {
    const rows = tickets?.data ?? [];

    return (
        <div className="table-wrapper">
            <div className="table-wrapper-header d-flex justify-content-end">
                <Link href={openUrl} className="btn ticket--btn">
                    <i className="far fa-list-alt" /> New Support
                </Link>
            </div>
            <div className="dashboard-table">
                <table className="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Last Reply</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {rows.length === 0 ? (
                            <tr>
                                <td colSpan={5} className="text-center text-muted py-4">No support tickets found.</td>
                            </tr>
                        ) : (
                            rows.map((ticket) => (
                                <tr key={ticket.id}>
                                    <td>
                                        <Link href={ticket.viewUrl} className="fw-bold">
                                            [Ticket#{ticket.ticket}] {ticket.subject}
                                        </Link>
                                    </td>
                                    <td><StatusBadge status={ticket.status} /></td>
                                    <td><StatusBadge status={ticket.priority} /></td>
                                    <td>{ticket.lastReply}</td>
                                    <td>
                                        <Link href={ticket.viewUrl} className="view-btn">
                                            <i className="las la-desktop" />
                                        </Link>
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
            {tickets?.links?.length > 3 && (
                <div className="table-wrapper-footer">
                    <Pagination links={tickets.links} />
                </div>
            )}
        </div>
    );
}
