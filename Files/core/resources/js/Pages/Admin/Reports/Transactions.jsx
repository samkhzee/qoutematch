import AdminLayout from '@/Components/Layout/AdminLayout';
import Pagination from '@/Components/Shared/Pagination';

export default function Transactions({ pageTitle, transactions }) {
    const rows = transactions?.data ?? [];

    return (
        <AdminLayout pageTitle={pageTitle}>
            <div className="card shadow-sm">
                <div className="table-responsive">
                    <table className="table table--light mb-0">
                        <thead>
                            <tr>
                                <th>TRX</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Charge</th>
                                <th>Type</th>
                                <th>Remark</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            {rows.length === 0 ? (
                                <tr><td colSpan={7} className="text-center text-muted py-4">No transactions found.</td></tr>
                            ) : rows.map((row) => (
                                <tr key={row.id}>
                                    <td>{row.trx}</td>
                                    <td>{row.user}</td>
                                    <td>{row.amount}</td>
                                    <td>{row.charge}</td>
                                    <td>{row.type}</td>
                                    <td><code className="small">{row.remark}</code></td>
                                    <td>{row.createdAt}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                {transactions?.links?.length > 3 && (
                    <div className="card-footer"><Pagination links={transactions.links} /></div>
                )}
            </div>
        </AdminLayout>
    );
}
