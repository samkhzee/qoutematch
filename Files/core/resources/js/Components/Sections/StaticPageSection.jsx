import { Link } from '@inertiajs/react';

export default function StaticPageSection({ data }) {
    if (!data?.heading) return null;

    return (
        <section className="static-page-section pt-120 pb-120">
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-lg-10">
                        <div className="section-heading two text-center mb-5">
                            <h1 className="section-heading__title">{data.heading}</h1>
                            {data.subheading && (
                                <p className="section-heading__desc">{data.subheading}</p>
                            )}
                        </div>
                        {data.body && (
                            <div className="static-page-body" dangerouslySetInnerHTML={{ __html: data.body }} />
                        )}
                        {data.buttonText && data.buttonUrl && (
                            <div className="text-center mt-4">
                                <Link href={data.buttonUrl} className="btn btn--base btn--lg">
                                    {data.buttonText}
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </section>
    );
}
