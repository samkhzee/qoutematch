import { useForm } from '@inertiajs/react';
import MasterLayout from '@/Components/Layout/MasterLayout';

export default function TrialTaskUpload({ pageTitle, task }) {
    const form = useForm({ task_file: null, comments: '' });

    const submit = (event) => {
        event.preventDefault();
        form.post(task.uploadUrl, { forceFormData: true });
    };

    return (
        <MasterLayout pageTitle={pageTitle}>
            <div className="row g-4">
                <div className="col-lg-8">
                    <div className="card custom--card">
                        <div className="card-body">
                            <h5>{task.title}</h5>
                            <p className="text-muted">{task.description}</p>
                            <form onSubmit={submit} encType="multipart/form-data">
                                <div className="form-group mb-3">
                                    <label className="form--label">Upload Task File</label>
                                    <input type="file" className="form-control" onChange={(e) => form.setData('task_file', e.target.files[0])} required />
                                </div>
                                <div className="form-group mb-3">
                                    <label className="form--label">Comment (Optional)</label>
                                    <textarea className="form-control form--control" rows={3} value={form.data.comments} onChange={(e) => form.setData('comments', e.target.value)} />
                                </div>
                                <button type="submit" className="btn btn--base" disabled={form.processing}>Submit Task</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div className="col-lg-4">
                    <div className="card custom--card">
                        <div className="card-body">
                            <h6>Customer</h6>
                            <p className="mb-1">{task.buyer.fullname}</p>
                            <small className="text-muted">{task.buyerStats.successJobs} / {task.buyerStats.totalJobs} projects completed ({task.buyerStats.successPercent}%)</small>
                        </div>
                    </div>
                </div>
            </div>
        </MasterLayout>
    );
}
