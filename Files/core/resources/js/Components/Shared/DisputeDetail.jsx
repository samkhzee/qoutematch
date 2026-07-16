import { Link } from '@inertiajs/react';
import StatusBadge from '@/Components/Shared/StatusBadge';

export default function DisputeDetail({ dispute }) {
    return (
        <div className="card custom--card">
            <div className="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 className="card-title mb-0">{dispute.subject}</h5>
                <StatusBadge status={dispute.status} />
            </div>
            <div className="card-body">
                <div className="row gy-3 mb-4">
                    <div className="col-md-6">
                        <span className="text-muted d-block">Type</span>
                        <strong>{dispute.typeLabel}</strong>
                    </div>
                    <div className="col-md-6">
                        <span className="text-muted d-block">Raised By</span>
                        <strong>{dispute.raisedBy}</strong>
                    </div>
                    <div className="col-md-6">
                        <span className="text-muted d-block">Provider</span>
                        <strong>{dispute.providerName}</strong>
                    </div>
                    <div className="col-md-6">
                        <span className="text-muted d-block">Request</span>
                        <strong>{dispute.jobTitle}</strong>
                    </div>
                    <div className="col-md-6">
                        <span className="text-muted d-block">Quote Amount</span>
                        <strong>{dispute.bidAmount}</strong>
                    </div>
                    <div className="col-md-6">
                        <span className="text-muted d-block">Submitted</span>
                        <strong>{dispute.createdAt}</strong>
                    </div>
                </div>

                <h6 className="mb-2">Description</h6>
                <div className="content-panel">
                    {dispute.description?.split('\n').map((line, i) => (
                        <span key={i}>{line}<br /></span>
                    ))}
                </div>

                {dispute.adminNote && (
                    <>
                        <h6 className="mb-2 mt-4">Admin Note</h6>
                        <div className="content-panel content-panel--plain">
                            {dispute.adminNote.split('\n').map((line, i) => (
                                <span key={i}>{line}<br /></span>
                            ))}
                        </div>
                    </>
                )}

                {dispute.resolvedAt && (
                    <p className="text-muted small mb-4">Closed: {dispute.resolvedAt}</p>
                )}

                <div className="d-flex flex-wrap gap-2">
                    <Link href={dispute.indexUrl} className="btn btn-outline--base btn-sm">All Disputes</Link>
                    {dispute.projectUrl && (
                        <Link href={dispute.projectUrl} className="btn btn--base btn-sm">View Project</Link>
                    )}
                </div>
            </div>
        </div>
    );
}
