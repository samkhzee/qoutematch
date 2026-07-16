import { useForm } from '@inertiajs/react';

export default function TrialTaskForm({ formData }) {
    const form = useForm({
        title: formData.title ?? '',
        amount: formData.amount ?? '',
        description: formData.description ?? '',
        deadline: formData.deadline ?? '',
    });

    const submit = (event) => {
        event.preventDefault();
        form.post(formData.submitUrl);
    };

    return (
        <div className="card custom--card">
            <div className="card-body">
                <form onSubmit={submit}>
                    <div className="form-group mb-3">
                        <label className="form--label">Title</label>
                        <input className="form-control form--control" value={form.data.title} onChange={(e) => form.setData('title', e.target.value)} required />
                    </div>
                    <div className="form-group mb-3">
                        <label className="form--label">Amount</label>
                        <input type="number" step="0.01" className="form-control form--control" value={form.data.amount} onChange={(e) => form.setData('amount', e.target.value)} required />
                    </div>
                    <div className="form-group mb-3">
                        <label className="form--label">Deadline</label>
                        <input type="date" className="form-control form--control" value={form.data.deadline} onChange={(e) => form.setData('deadline', e.target.value)} required />
                    </div>
                    <div className="form-group mb-3">
                        <label className="form--label">Description</label>
                        <textarea className="form-control form--control" rows={4} value={form.data.description} onChange={(e) => form.setData('description', e.target.value)} required />
                    </div>
                    <div className="d-flex gap-2">
                        <button type="submit" className="btn btn--base" disabled={form.processing}>Save Task</button>
                        <a href={formData.indexUrl} className="btn btn-outline--dark">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    );
}
