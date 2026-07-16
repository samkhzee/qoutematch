import { Link, useForm } from '@inertiajs/react';
import AdminLayout from '@/Components/Layout/AdminLayout';

function FieldList({ fields }) {
    if (!fields?.length) return <p className="text-muted mb-0">No fields.</p>;
    return (
        <dl className="row mb-0">
            {fields.map((field, index) => (
                <div key={index} className="col-md-6 mb-3">
                    <dt className="text-muted small">{field.name}</dt>
                    <dd className="mb-0">
                        {field.isFile ? (
                            <a href={field.value} target="_blank" rel="noreferrer">Download</a>
                        ) : (
                            field.value
                        )}
                    </dd>
                </div>
            ))}
        </dl>
    );
}

export default function Detail({ pageTitle, bid }) {
    const deleteForm = useForm({});

    return (
        <AdminLayout pageTitle={pageTitle}>
            <div className="mb-3">
                <Link href={bid.indexUrl} className="btn btn-sm btn-outline--dark">← All quotes</Link>
            </div>

            <div className="row gy-4">
                <div className="col-lg-8">
                    <div className="card shadow-sm">
                        <div className="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 className="mb-0">Quote #{bid.id}</h5>
                            <span className={bid.status.class}>{bid.status.label}</span>
                        </div>
                        <div className="card-body">
                            <div className="row gy-3 mb-4">
                                <div className="col-md-6"><span className="text-muted d-block">Amount</span><strong>{bid.amount}</strong></div>
                                <div className="col-md-6"><span className="text-muted d-block">Estimated time</span><strong>{bid.estimatedTime ?? '—'}</strong></div>
                                <div className="col-md-6"><span className="text-muted d-block">Submitted</span><strong>{bid.createdAt}</strong></div>
                                {bid.provider && (
                                    <div className="col-md-6">
                                        <span className="text-muted d-block">Provider</span>
                                        <a href={bid.provider.detailUrl}>{bid.provider.fullname} (@{bid.provider.username})</a>
                                    </div>
                                )}
                                {bid.buyer && (
                                    <div className="col-md-6">
                                        <span className="text-muted d-block">Customer</span>
                                        <a href={bid.buyer.detailUrl}>{bid.buyer.fullname} (@{bid.buyer.username})</a>
                                    </div>
                                )}
                                {bid.job && (
                                    <div className="col-md-6">
                                        <span className="text-muted d-block">Request</span>
                                        <a href={bid.job.detailUrl}>{bid.job.title}</a>
                                    </div>
                                )}
                            </div>

                            {bid.bidQuote && (
                                <>
                                    <h6>Cover note</h6>
                                    <div className="content-panel mb-4">{bid.bidQuote}</div>
                                </>
                            )}

                            {bid.quoteBreakdown?.costLines?.length > 0 && (
                                <>
                                    <h6>Cost breakdown</h6>
                                    <table className="table table-sm mb-4">
                                        <tbody>
                                            {bid.quoteBreakdown.costLines.map((line, index) => (
                                                <tr key={index}>
                                                    <td>{line.name}</td>
                                                    <td className="text-end">{line.valueFormatted}</td>
                                                </tr>
                                            ))}
                                            <tr className="fw-bold">
                                                <td>Total</td>
                                                <td className="text-end">{bid.quoteBreakdown.computedTotalFormatted}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </>
                            )}

                            <h6>Quote form</h6>
                            <FieldList fields={bid.quoteFields} />

                            <h6 className="mt-4">Original request</h6>
                            <FieldList fields={bid.requestFields} />
                        </div>
                    </div>
                </div>

                <div className="col-lg-4">
                    <div className="card shadow-sm">
                        <div className="card-header bg-white"><h6 className="mb-0">Moderation</h6></div>
                        <div className="card-body">
                            <form
                                onSubmit={(e) => {
                                    e.preventDefault();
                                    if (window.confirm('Delete this quote?')) {
                                        deleteForm.post(bid.actions.deleteUrl);
                                    }
                                }}
                            >
                                <button type="submit" className="btn btn--danger btn-sm w-100" disabled={deleteForm.processing}>
                                    Delete quote
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
