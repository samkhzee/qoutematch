export default function TrustSection({ data }) {
    return (
        <section className="trust-section my-120">
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-lg-8">
                        <div className="section-heading two text-center">
                            <h2 className="section-heading__title s-highlight" data-s-break="-1" data-s-length="1">
                                {data.heading}
                            </h2>
                            <p className="section-heading__desc">{data.subheading}</p>
                        </div>
                    </div>
                </div>
                <div className="row gy-4 mt-2">
                    {data.items?.map((item, index) => (
                        <div key={index} className="col-lg-4 col-md-6">
                            <div className="trust-card text-center h-100">
                                <span className="trust-card__icon">
                                    <i className={item.icon}></i>
                                </span>
                                <h5 className="trust-card__title">{item.title}</h5>
                                <p className="trust-card__desc">{item.content}</p>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
