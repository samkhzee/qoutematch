import StatusBadge from '@/Components/Shared/StatusBadge';

export default function TrialTaskDetail({ task }) {
    return (
        <div className="card custom--card">
            <div className="card-header d-flex justify-content-between align-items-center">
                <h5 className="mb-0">Task: {task.title}</h5>
                <StatusBadge status={task.status} />
            </div>
            <div className="card-body">
                <p>{task.description}</p>
                <ul className="list-group list-group-flush mb-3">
                    <li className="list-group-item d-flex justify-content-between"><span>Amount</span><strong>{task.amount}</strong></li>
                    <li className="list-group-item d-flex justify-content-between"><span>Deadline</span><strong>{task.deadline}</strong></li>
                    <li className="list-group-item d-flex justify-content-between"><span>Assigned</span><strong>{task.assignedAt}</strong></li>
                    <li className="list-group-item d-flex justify-content-between"><span>Uploaded</span><strong>{task.uploadedAt ?? '—'}</strong></li>
                </ul>
                {task.file && (
                    <a href={task.file.downloadUrl} className="btn btn--base btn--sm">Download submitted file</a>
                )}
                <div className="mt-3">
                    <a href={task.indexUrl} className="btn btn-outline--dark btn--sm">Back to list</a>
                </div>
            </div>
        </div>
    );
}
