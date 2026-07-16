import JobPostShell from '@/Components/Layout/JobPostShell';
import JobPostWizard from '@/Components/Jobs/JobPostWizard';

export default function Index({
    pageTitle,
    categories,
    categoryForms,
    skills,
    draft,
    currencyText,
    wizardPhase = 0,
}) {
    return (
        <JobPostShell pageTitle={pageTitle} guestMode wizard>
            <div className="job-post-content job-post-content--wizard">
                <JobPostWizard
                    categories={categories}
                    categoryForms={categoryForms}
                    skills={skills}
                    draft={draft}
                    currencyText={currencyText}
                    initialPhase={wizardPhase}
                    wizardPhase={wizardPhase}
                />
            </div>
        </JobPostShell>
    );
}
