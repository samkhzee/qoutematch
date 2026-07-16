import { useForm } from '@inertiajs/react';
import MasterLayout from '@/Components/Layout/MasterLayout';

export default function Upload({ pageTitle, project, buyer, buyerStats }) {
    const form = useForm({
        project_file: null,
        comments: project.comments ?? '',
    });

    const submit = (event) => {
        event.preventDefault();
        form.post(project.uploadStoreUrl || project.uploadUrl, { forceFormData: true });
    };

    return (
        <MasterLayout pageTitle={pageTitle}>
            <div className="project-upload-shell">
                <div className="row g-4 align-items-start">
                    <div className="col-lg-8">
                        <div className="profile-bio">
                            <div className="profile-bio__top">
                                <h5>{project.jobTitle}</h5>
                            </div>
                            <div className="profile-bio__wrapper">
                                <form onSubmit={submit} encType="multipart/form-data">
                                    <div className="row">
                                        <div className="form-group d-flex flex-wrap justify-content-between p-3 rounded gap-3">
                                            <div className="info-block text-center">
                                                <h6 className="mb-1 text--primary fw-semibold">Project Assigned at</h6>
                                                <small className="text-muted">{project.assignedAt}</small>
                                            </div>
                                            <div className="info-block text-center">
                                                <h6 className="mb-1 text--success fw-semibold">Estimated Time</h6>
                                                <small className="text-muted">{project.bid.estimatedTime}</small>
                                            </div>
                                        </div>
                                        <div className="form-group">
                                            <label className="form--label" htmlFor="fileInput">Upload Project File</label>
                                            <input
                                                type="file"
                                                className="form-control"
                                                id="fileInput"
                                                accept=".zip,.rar,.pdf,.doc,.docx,.xls,.xlsx,.7zip"
                                                onChange={(e) => form.setData('project_file', e.target.files[0])}
                                                required
                                            />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="description" className="form--label">Comment (Optional)</label>
                                            <textarea
                                                id="description"
                                                className="form-control form--control"
                                                rows={3}
                                                value={form.data.comments}
                                                onChange={(e) => form.setData('comments', e.target.value)}
                                            />
                                        </div>
                                    </div>
                                    <div className="text-end">
                                        <button className="btn--base btn" type="submit" disabled={form.processing}>
                                            Upload Project
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div className="col-lg-4">
                        <div className="sidebar-wrapper">
                            <div className="sidebar-item buyer-info-item">
                                <h6 className="sidebar-item__title">Buyer Profile</h6>
                                <div className="buyer-info">
                                    <div className="buyer-info__thumb">
                                        <img src={buyer.image} alt="" />
                                    </div>
                                    <div className="buyer-info__content">
                                        <p className="buyer-info__name">{buyer.fullname}</p>
                                        <div className="location">
                                            <div className="text">{buyer.country} |</div>
                                            <small>{buyer.address}</small>
                                        </div>
                                        <p className="small text-muted mt-2">
                                            {buyerStats.successJobs} / {buyerStats.totalJobs} projects completed
                                            ({buyerStats.successPercent}%)
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </MasterLayout>
    );
}
