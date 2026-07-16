import { router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import Pagination from '@/Components/Shared/Pagination';
import StatusBadge from '@/Components/Shared/StatusBadge';

export default function WithdrawHistory({ withdrawals, indexUrl }) {
    const rows = withdrawals?.data ?? [];
    const { data, setData } = useForm({ search: '' });
    const [showFilter, setShowFilter] = useState(false);

    const submitSearch = (event) => {
        event.preventDefault();
        router.get(indexUrl, data, { preserveState: true });
    };

    return (
        <div className="table-wrapper">
            <div className="table-wrapper-header d-flex justify-content-end">
                <button type="button" className="btn btn--base btn--sm" onClick={() => setShowFilter((v) => !v)}>
                    <i className="las la-search" /> Search
                </button>
            </div>
            {showFilter && (
                <form className="mb-3" onSubmit={submitSearch}>
                    <input
                        className="form-control form--control"
                        placeholder="Search by TRX"
                        value={data.search}
                        onChange={(e) => setData('search', e.target.value)}
                    />
                </form>
            )}
            <table className="table table--responsive--md">
                <thead>
                    <tr>
                        <th>TRX</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Charge</th>
                        <th>Receivable</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    {rows.length === 0 ? (
                        <tr><td colSpan={7} className="text-center text-muted py-4">No withdrawals found.</td></tr>
                    ) : (
                        rows.map((row) => (
                            <tr key={row.id}>
                                <td><strong>{row.trx}</strong></td>
                                <td>{row.method}</td>
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
            {withdrawals?.links?.length > 3 && <Pagination links={withdrawals.links} />}
        </div>
    );
}
