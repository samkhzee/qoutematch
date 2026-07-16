import { useForm } from '@inertiajs/react';
import AdminLayout from '@/Components/Layout/AdminLayout';

export default function Index({ pageTitle, methods }) {
    const rows = methods ?? [];

    return (
        <AdminLayout pageTitle={pageTitle}>
            <div className="card shadow-sm">
                <div className="table-responsive">
                    <table className="table table--light mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Currency</th>
                                <th>Rate</th>
                                <th>Min</th>
                                <th>Max</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {rows.length === 0 ? (
                                <tr><td colSpan={7} className="text-center text-muted py-4">No withdrawal methods found.</td></tr>
                            ) : rows.map((row) => (
                                <MethodRow key={row.id} row={row} />
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AdminLayout>
    );
}

function MethodRow({ row }) {
    const statusForm = useForm({});

    return (
        <tr>
            <td>{row.name}</td>
            <td>{row.currency}</td>
            <td>{row.rate}</td>
            <td>{row.minLimit}</td>
            <td>{row.maxLimit}</td>
            <td><span className={row.status.class}>{row.status.label}</span></td>
            <td>
                <button type="button" className="btn btn-sm btn-outline--warning" disabled={statusForm.processing}
                    onClick={() => statusForm.post(row.statusUrl)}>Toggle Status</button>
            </td>
        </tr>
    );
}
