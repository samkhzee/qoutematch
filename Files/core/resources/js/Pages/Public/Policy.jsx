import FrontendLayout from '@/Components/Layout/FrontendLayout';

export default function Policy({ pageTitle, seo, content }) {
    return (
        <FrontendLayout pageTitle={pageTitle} seo={seo}>
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
