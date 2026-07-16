import Pagination from '@/Components/Shared/Pagination';
import StatusBadge from '@/Components/Shared/StatusBadge';

export default function DepositHistory({ deposits }) {
    const rows = deposits?.data ?? [];

    return (
        <div className="table-wrapper">
            <table className="table table--responsive--md">
                <thead>
                    <tr>
                        <th>TRX</th>
                        <th>Gateway</th>
                        <th>Amount</th>
                        <th>Charge</th>
                        <th>Final Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    {rows.length === 0 ? (
                        <tr><td colSpan={7} className="text-center text-muted py-4">No deposits found.</td></tr>
                    ) : (
                        rows.map((row) => (
                            <tr key={row.trx}>
                                <td><strong>{row.trx}</strong></td>
                                <td>{row.gateway}</td>
                                <td>{row.amount}</td>
                                <td>{row.charge}</td>
                                <td>{row.finalAmount}</td>
                                <td><StatusBadge status={row.status} /></td>
                                <td>{row.createdAt}</td>
                            </tr>
                        ))
                    )}
                </tbody>
            </table>
            {deposits?.links?.length > 3 && <Pagination links={deposits.links} />}
        </div>
    );
}
