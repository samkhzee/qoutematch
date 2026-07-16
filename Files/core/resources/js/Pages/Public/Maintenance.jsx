import AppLayout from '@/Components/Layout/AppLayout';

export default function Maintenance({ pageTitle, heading, description, image }) {
    return (
        <AppLayout pageTitle={pageTitle} showPreloader={false}>
            <section className="maintenance-section">
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-lg-8 text-center">
                            {image && <img src={image} alt="" className="mb-4" />}
                            <h2>{heading}</h2>
                            <div dangerouslySetInnerHTML={{ __html: description }} />
                        </div>
                    </div>
                </div>
            </section>
        </AppLayout>
    );
}
