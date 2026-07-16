import { Link, usePage } from '@inertiajs/react';

const steps = [
    { key: 1, label: 'Request Details', routeKey: 'details', storeRouteKey: 'detailsStore', minStep: 1 },
    { key: 2, label: 'Provider Preferences', routeKey: 'preferences', storeRouteKey: 'preferencesStore', minStep: 2 },
    { key: 3, label: 'Budget & Publish', routeKey: 'budget', storeRouteKey: 'budgetStore', minStep: 3 },
];

export default function JobPostSteps({ currentStep, jobId, guestMode = false }) {
    const { routes, jobPostRoutes } = usePage().props;
    const routeMap = guestMode && jobPostRoutes ? jobPostRoutes : {
        details: routes?.buyerJobPostDetails,
        preferences: routes?.buyerJobPostPreferences,
        budget: routes?.buyerJobPostBudget,
    };

    return (
        <div className="mb-4">
            <h6>{guestMode ? 'Post your job in 3 simple steps' : 'Complete these 3 steps to post your request'}</h6>
            <ul className="page-list pt-3">
                {steps.map((step) => {
                    const isCurrent = step.key === currentStep;
                    const unlocked = guestMode
                        ? currentStep >= step.minStep
                        : jobId && currentStep >= step.minStep;
                    const baseHref = routeMap?.[step.routeKey] ?? '#';
                    const href = guestMode || !jobId
                        ? baseHref
                        : `${baseHref}/${jobId}`;

                    return (
                        <li
                            key={step.key}
                            className={`nav-item ${currentStep >= step.key || isCurrent ? 'active' : ''} ${isCurrent ? 'current' : ''}`}
                        >
                            {unlocked || step.key === 1 ? (
                                isCurrent ? (
                                    <span className={`nav-link ${isCurrent ? 'active' : ''}`}>
                                        <span className="profile-item__title">{step.label}</span>
                                    </span>
                                ) : (
                                    <Link className={`nav-link ${isCurrent ? 'active' : ''}`} href={href}>
                                        <span className="profile-item__title">{step.label}</span>
                                    </Link>
                                )
                            ) : (
                                <span className="nav-link disabled">
                                    <span className="profile-item__title">{step.label}</span>
                                </span>
                            )}
                        </li>
                    );
                })}
            </ul>
        </div>
    );
}
