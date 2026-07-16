import FrontendLayout from '@/Components/Layout/FrontendLayout';
import BuyerMasterLayout from '@/Components/Layout/BuyerMasterLayout';

function WizardIntro() {
    return (
        <div className="post-job-wizard-intro text-center mb-4">
            <h1 className="post-job-wizard-intro__title mb-2">Post a job</h1>
            <p className="post-job-wizard-intro__text mb-0 text-muted">
                Answer one question at a time — choose your option, then tap Next.
            </p>
        </div>
    );
}

export default function JobPostShell({ children, pageTitle, guestMode = false, wizard = false }) {
    if (guestMode) {
        return (
            <FrontendLayout pageTitle={pageTitle}>
                <section className={`post-job-section py-5${wizard ? ' post-job-section--wizard' : ''}`}>
                    <div className={wizard ? 'container container--narrow' : 'container'}>
                        {!wizard && (
                            <div className="post-job-hero mb-4">
                                <p className="post-job-hero__eyebrow mb-2">Free to post</p>
                                <h1 className="post-job-hero__title mb-2">Post your job and get quotes</h1>
                                <p className="post-job-hero__text mb-0 text-muted">
                                    No login required. Tell us what you need, compare quotes from verified providers, and hire with confidence.
                                </p>
                            </div>
                        )}
                        {wizard && <WizardIntro />}
                        {children}
                    </div>
                </section>
            </FrontendLayout>
        );
    }

    return (
        <BuyerMasterLayout pageTitle={pageTitle}>
            {wizard ? (
                <section className="post-job-section post-job-section--wizard py-4">
                    <div className="container container--narrow px-0">
                        <WizardIntro />
                        {children}
                    </div>
                </section>
            ) : (
                children
            )}
        </BuyerMasterLayout>
    );
}
