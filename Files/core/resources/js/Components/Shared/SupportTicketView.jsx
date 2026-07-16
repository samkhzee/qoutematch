import { router, useForm } from '@inertiajs/react';
import StatusBadge from '@/Components/Shared/StatusBadge';

export default function SupportTicketView({ ticket, messages = [], role }) {
    const form = useForm({ message: '', attachments: [] });

    const submitReply = (event) => {
        event.preventDefault();
        form.transform((data) => {
            const payload = new FormData();
            payload.append('message', data.message);
            (data.attachments || []).forEach((file) => payload.append('attachments[]', file));
            return payload;
        });
        form.post(ticket.replyUrl, { forceFormData: true, preserveScroll: true });
    };

    const closeTicket = () => {
        if (!window.confirm('Are you sure to close this ticket?')) return;
        router.post(ticket.closeUrl);
    };

    return (
        <div className="row justify-content-center gy-4 support-ticket-view">
            <div className="col-lg-9">
                {/* Ticket information summary */}
                <div className="card custom--card mb-4">
                    <div className="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h5 className="card-title mb-0">Ticket #{ticket.ticket}</h5>
                        <StatusBadge status={ticket.status} />
                    </div>
                    <div className="card-body">
                        <div className="row gy-2">
                            <div className="col-sm-6"><strong>Priority:</strong> <StatusBadge status={ticket.priority} /></div>
                            <div className="col-sm-6"><strong>Opened At:</strong> {ticket.createdAt}</div>
                            <div className="col-sm-6"><strong>Last Reply:</strong> {ticket.lastReply}</div>
                        </div>
                        <div className="d-flex flex-wrap align-items-center gap-2 mt-3">
                            {!ticket.isClosed && (
                                <button type="button" className="btn btn--danger btn--sm" onClick={closeTicket}>
                                    Close Support
                                </button>
                            )}
                            <a href={ticket.indexUrl} className="btn btn-outline--base btn--sm">All Tickets</a>
                        </div>
                    </div>
                </div>

                {/* Conversation */}
                <div className="card custom--card mb-4">
                    <div className="card-header">
                        <h5 className="card-title mb-0">Conversation</h5>
                    </div>
                    <div className="card-body">
                        {messages.length === 0 ? (
                            <p className="text-muted mb-0">No messages yet.</p>
                        ) : (
                            messages.map((message) => (
                                <div key={message.id} className={`chat-item ${message.isAdmin ? '' : 'reply'}`}>
                                    <span className="chat-item__thumb">
                                        <img src={message.senderImage} alt="" />
                                    </span>
                                    <div className="chat-item__content">
                                        <p className="chat-item__name">{message.senderName}</p>
                                        <p className="chat-item__time"><small><i className="far fa-clock" /> {message.createdAt}</small></p>
                                        <p className="chat-item__message">{message.message}</p>
                                        {message.attachments?.map((file) => (
                                            <div key={file.id} className="atach-preview d-inline-flex align-items-center gap-2 me-2">
                                                <img src={file.previewImage} alt="" width="32" height="32" />
                                                <a href={file.downloadUrl} className="btn btn--sm btn-outline--base">Download</a>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            ))
                        )}
                    </div>
                </div>

                {/* Reply box: textarea with the Send Reply button directly beneath it */}
                {ticket.isClosed ? (
                    <div className="alert alert-warning mb-0">This ticket is closed. You can no longer reply.</div>
                ) : (
                    <div className="card custom--card">
                        <div className="card-header">
                            <h5 className="card-title mb-0">Reply to this ticket</h5>
                        </div>
                        <div className="card-body">
                            <form onSubmit={submitReply} encType="multipart/form-data">
                                <div className="form-group mb-3">
                                    <label className="form--label required">Message</label>
                                    <textarea
                                        id="ticket-reply-box"
                                        className="form--control"
                                        rows={4}
                                        placeholder="Write your reply here..."
                                        value={form.data.message}
                                        onChange={(e) => form.setData('message', e.target.value)}
                                        required
                                    />
                                </div>
                                <div className="form-group mb-3">
                                    <label className="form--label">Attachments (optional)</label>
                                    <input
                                        type="file"
                                        className="form--control"
                                        multiple
                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                        onChange={(e) => form.setData('attachments', [...e.target.files])}
                                    />
                                </div>
                                <button type="submit" className="btn btn--base btn--lg w-100" disabled={form.processing}>
                                    <i className="las la-paper-plane" /> {form.processing ? 'Sending...' : 'Send Reply'}
                                </button>
                            </form>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
