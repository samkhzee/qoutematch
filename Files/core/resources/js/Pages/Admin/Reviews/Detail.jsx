import { Link, useForm } from '@inertiajs/react';
import AdminLayout from '@/Components/Layout/AdminLayout';

export default function Detail({ pageTitle, review }) {
    const approveForm = useForm({});
    const hideForm = useForm({ admin_note: review.adminNote ?? '' });

    return (
        <AdminLayout pageTitle={pageTitle}>
            <div className="mb-3">
                <Link href={review.indexUrl} className="btn btn-sm btn-outline--dark">← Reviews</Link>
            </div>

            <div className="row gy-4">
                <div className="col-lg-8">
                    <div className="card shadow-sm">
                        <div className="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 className="mb-0">Review — {review.rating}/5</h5>
                            <span className={review.status.class}>{review.status.label}</span>
                        </div>
                        <div className="card-body">
                            <div className="row gy-3 mb-4">
                                <div className="col-md-6"><span className="text-muted d-block">Submitted</span><strong>{review.createdAt}</strong></div>
                                {review.provider && (
                                    <div className="col-md-6">
                                        <span className="text-muted d-block">Provider</span>
                                        <a href={review.provider.detailUrl}>{review.provider.fullname} (@{review.provider.username})</a>
                                    </div>
                                )}
                                {review.buyer && (
                                    <div className="col-md-6">
                                        <span className="text-muted d-block">Customer</span>
                                        <a href={review.buyer.detailUrl}>{review.buyer.fullname} (@{review.buyer.username})</a>
                                    </div>
                                )}
                                {review.job && (
                                    <div className="col-md-6">
                                        <span className="text-muted d-block">Request</span>
                                        <a href={review.job.detailUrl}>{review.job.title}</a>
                                    </div>
                                )}
                            </div>

                            <h6>Comment</h6>
                            <div className="content-panel mb-4">{review.review}</div>

                            {review.scores?.length > 0 && (
                                <>
                                    <h6>Dimension scores</h6>
                                    <ul className="list-unstyled mb-0">
                                        {review.scores.map((score) => (
                                            <li key={score.label} className="mb-2">
                                                <span className="text-muted">{score.label}:</span> <strong>{score.score}/5</strong>
                                            </li>
                                        ))}
                                    </ul>
                                </>
                            )}
                        </div>
                    </div>
                </div>

                <div className="col-lg-4">
                    <div className="card shadow-sm">
                        <div className="card-header bg-white"><h6 className="mb-0">Moderation</h6></div>
                        <div className="card-body">
                            {review.actions.approveUrl && (
                                <form
                                    className="mb-4"
                                    onSubmit={(e) => {
                                        e.preventDefault();
                                        approveForm.post(review.actions.approveUrl);
                                    }}
                                >
                                    <button type="submit" className="btn btn--success btn-sm w-100" disabled={approveForm.processing}>
                                        Approve & publish
                                    </button>
                                </form>
                            )}

                            {review.actions.hideUrl && (
                                <form
                                    onSubmit={(e) => {
                                        e.preventDefault();
                                        hideForm.post(review.actions.hideUrl);
                                    }}
                                >
                                    <div className="form-group mb-2">
                                        <label className="small">Hide note (optional)</label>
                                        <textarea
                                            className="form-control form-control-sm"
                                            rows={3}
                                            value={hideForm.data.admin_note}
                                            onChange={(e) => hideForm.setData('admin_note', e.target.value)}
                                        />
                                    </div>
                                    <button type="submit" className="btn btn--danger btn-sm w-100" disabled={hideForm.processing}>
                                        Hide from profile
                                    </button>
                                </form>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
