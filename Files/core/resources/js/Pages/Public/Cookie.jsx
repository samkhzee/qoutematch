import FrontendLayout from '@/Components/Layout/FrontendLayout';

export default function Cookie({ pageTitle, content }) {
    return (
        <FrontendLayout pageTitle={pageTitle}>
            <section className="my-120 privacy-page">
                <div className="container">
                    <div className="row">
                        <div className="col-md-12" dangerouslySetInnerHTML={{ __html: content }} />
                    </div>
                </div>
            </section>
        </FrontendLayout>
    );
}
