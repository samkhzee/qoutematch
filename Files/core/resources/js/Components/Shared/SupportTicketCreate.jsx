import { useForm } from '@inertiajs/react';

export default function SupportTicketCreate({ storeUrl, indexUrl }) {
    const form = useForm({
        priority: '2',
        subject: '',
        message: '',
        attachments: [],
    });

    const submit = (event) => {
        event.preventDefault();
        const data = new FormData();
        data.append('priority', form.data.priority);
        data.append('subject', form.data.subject);
        data.append('message', form.data.message);
        (form.data.attachments || []).forEach((file) => data.append('attachments[]', file));
        form.transform(() => Object.fromEntries(data.entries()));
        form.post(storeUrl, { forceFormData: true });
    };

    return (
        <div className="card custom--card">
            <div className="card-body">
                <form onSubmit={submit} encType="multipart/form-data">
                    <div className="form-group mb-3">
                        <label className="form--label d-block required">Priority</label>
                        <div className="d-flex gap-3 flex-wrap">
                            {[
                                { value: '1', label: 'Low' },
                                { value: '2', label: 'Medium' },
                                { value: '3', label: 'High' },
                            ].map((opt) => (
                                <label key={opt.value} className="form-check">
                                    <input
                                        type="radio"
                                        name="priority"
                                        className="form-check-input"
                                        value={opt.value}
                                        checked={form.data.priority === opt.value}
                                        onChange={(e) => form.setData('priority', e.target.value)}
                                        required
                                    />
                                    <span className="form-check-label">{opt.label}</span>
                                </label>
                            ))}
                        </div>
                    </div>
                    <div className="form-group mb-3">
                        <label className="form--label required">Subject</label>
                        <input
                            className="form--control"
                            value={form.data.subject}
                            onChange={(e) => form.setData('subject', e.target.value)}
                            required
                        />
                    </div>
                    <div className="form-group mb-3">
                        <label className="form--label required">Message</label>
                        <textarea
                            className="form--control"
                            rows={5}
                            value={form.data.message}
                            onChange={(e) => form.setData('message', e.target.value)}
                            required
                        />
                    </div>
                    <div className="form-group mb-3">
                        <label className="form--label">Attachments</label>
                        <input
                            type="file"
                            className="form--control"
                            multiple
                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                            onChange={(e) => form.setData('attachments', [...e.target.files])}
                        />
                    </div>
                    <div className="d-flex gap-2">
                        <button type="submit" className="btn btn--base" disabled={form.processing}>Submit Ticket</button>
                        <a href={indexUrl} className="btn btn-outline--dark">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    );
}
