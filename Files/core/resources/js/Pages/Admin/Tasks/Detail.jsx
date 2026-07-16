import { Link } from '@inertiajs/react';
import AdminLayout from '@/Components/Layout/AdminLayout';

export default function Detail({ pageTitle, task }) {
    return (
        <AdminLayout pageTitle={pageTitle}>
            <div className="mb-3">
                <Link href={task.indexUrl} className="btn btn-sm btn-outline--dark">← Trial Tasks</Link>
            </div>

            <div className="card shadow-sm">
                <div className="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 className="mb-0">{task.title}</h5>
                    <span className={task.status.class}>{task.status.label}</span>
                </div>
                <div className="card-body">
                    <div className="row gy-3 mb-4">
                        {task.job && (
                            <div className="col-md-6"><span className="text-muted d-block">Request</span><strong>{task.job.title}</strong></div>
                        )}
                        <div className="col-md-6"><span className="text-muted d-block">Customer</span><strong>{task.buyer ?? '—'}</strong></div>
                        <div className="col-md-6"><span className="text-muted d-block">Provider</span><strong>{task.provider ?? '—'}</strong></div>
                        <div className="col-md-6"><span className="text-muted d-block">Created</span><strong>{task.createdAt}</strong></div>
                    </div>
                    <h6>Description</h6>
                    <div className="content-panel">{task.description}</div>
                </div>
            </div>
        </AdminLayout>
    );
}
