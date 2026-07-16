import { Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import Pagination from '@/Components/Shared/Pagination';
import StatusBadge from '@/Components/Shared/StatusBadge';
import VerificationBadges from '@/Components/Shared/VerificationBadges';

export default function ProjectList({ projects, filters, statusOptions = [], role = 'buyer', indexUrl }) {
    const rows = projects?.data ?? [];
    const { data, setData } = useForm({
        search: filters?.search ?? '',
        status: filters?.status ?? '',
        date: filters?.date ?? '',
    });
    const [showFilter, setShowFilter] = useState(false);

    const submitFilter = (event) => {
        event.preventDefault();
        router.get(indexUrl, data, { preserveState: true });
    };

    return (
        <div className="table-wrapper">
            {role === 'buyer' && (
                <div className="table-wrapper-header gap-3">
                    <div className="show-filter mb-3 text-end">
                        <button type="button" className="btn btn--base showFilterBtn btn--sm" onClick={() => setShowFilter((v) => !v)}>
                            <i className="las la-filter" /> Filter
                        </button>
                    </div>
                    {showFilter && (
                        <div className="responsive-filter-card my-4">
                            <form onSubmit={submitFilter}>
                                <div className="d-flex flex-wrap gap-4">
                                    <div className="flex-grow-1">
                                        <input
                                            type="search"
                                            name="search"
                                            value={data.search}
                                            onChange={(e) => setData('search', e.target.value)}
                                            placeholder="Search by Job"
                                            className="form-control form--control"
                                        />
                                    </div>
                                    <div className="flex-grow-1">
                                        <select
                                            name="status"
                                            value={data.status}
                                            onChange={(e) => setData('status', e.target.value)}
                                            className="form-control form--control"
                                        >
                                            {statusOptions.map((opt) => (
                                                <option key={opt.value} value={opt.value}>{opt.label}</option>
                                            ))}
                                        </select>
                                    </div>
                                    <div className="flex-grow-1">
                                        <input
                                            name="date"
                                            type="search"
                                            value={data.date}
                                            onChange={(e) => setData('date', e.target.value)}
                                            className="form-control form--control"
                                            placeholder="Start Date - End Date"
                                        />
                                    </div>
                                    <div className="flex-grow-1 align-self-end">
                                        <button className="btn btn--base w-100" type="submit">
                                            <i className="las la-filter" /> Filter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    )}
                </div>
            )}

            {role === 'freelancer' && (
                <div className="table-wrapper-header d-flex justify-content-end">
                    <form className="table-search" onSubmit={submitFilter}>
                        <input
                            className="form-control form--control"
                            type="search"
                            value={data.search}
                            onChange={(e) => setData('search', e.target.value)}
                            placeholder="Search Here..."
                        />
                        <button className="table-search-text" type="submit">
                            <i className="las la-search" />
                        </button>
                    </form>
                </div>
            )}

            <div className="table-responsive">
                <table className="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>{role === 'buyer' ? 'Freelancer' : 'Buyer'}</th>
                            <th>Estimate Time</th>
                            <th>Budget</th>
                            <th>Status</th>
                            <th>Assigned at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {rows.length === 0 ? (
                            <tr>
                                <td colSpan={7} className="text-center text-muted py-4">No projects found.</td>
                            </tr>
                        ) : (
                            rows.map((project) => (
                                <tr key={project.id}>
                                    <td data-label="Job"><span className="clamping">{project.jobTitle}</span></td>
                                    <td data-label={role === 'buyer' ? 'Freelancer' : 'Buyer'}>
                                        <span className="d-inline-flex align-items-center flex-wrap gap-1">
                                            {project.counterparty.fullname}
                                            {project.counterparty.verificationBadges && (
                                                <VerificationBadges badges={project.counterparty.verificationBadges} compact />
                                            )}
                                        </span>
                                        {project.counterparty.profileUrl ? (
                                            <a className="d-block" href={project.counterparty.profileUrl} target="_blank" rel="noreferrer">
                                                {project.counterparty.username}
                                            </a>
                                        ) : (
                                            <span className="d-block small text-muted">{project.counterparty.username}</span>
                                        )}
                                    </td>
                                    <td data-label="Estimate Time"><span className="clamping">{project.estimatedTime}</span></td>
                                    <td data-label="Budget">
                                        <div className="bid-budget-cell">
                                            <span className="bid-budget-cell__amount">{project.bidAmount}</span>
                                            <span className={`bid-budget-cell__type ${project.customBudget ? 'text--info' : 'text--primary'}`}>
                                                {project.customBudget ? 'Customized' : 'Fixed'}
                                            </span>
                                        </div>
                                    </td>
                                    <td data-label="Status"><StatusBadge status={project.status} /></td>
                                    <td data-label="Assigned at">{project.assignedAt}</td>
                                    <td data-label="Action">
                                        <div className="btn-group gap-2">
                                            {project.canViewDetail ? (
                                                <Link href={project.detailUrl} className="view-btn" title="Project Details">
                                                    <i className="las la-desktop" />
                                                </Link>
                                            ) : (
                                                <span className="view-btn disabled"><i className="las la-desktop" /></span>
                                            )}
                                            {project.uploadUrl && (
                                                <Link href={project.uploadUrl} className="view-btn" title="Upload">
                                                    <i className="las la-upload" />
                                                </Link>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
            {projects?.links?.length > 3 && (
                <div className="table-wrapper-footer">
                    <Pagination links={projects.links} />
                </div>
            )}
        </div>
    );
}
