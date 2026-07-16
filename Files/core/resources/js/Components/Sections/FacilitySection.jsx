export default function FacilitySection({ data }) {
    return (
        <div className="facility-section my-120">
            <div className="container">
                <div className="section-heading two">
                    <h2 className="section-heading__title s-highlight" data-s-break="-1" data-s-length="1">{data.heading}</h2>
                    <p className="section-heading__desc">{data.subheading}</p>
                </div>
                <div className="row gy-4">
                    {data.elements?.map((item, index) => (
                        <div key={index} className="col-lg-4 col-sm-6">
                            <div className="facility-item">
                                <span className="facility-item__icon" dangerouslySetInnerHTML={{ __html: item.icon }} />
                                <div className="facility-item__content">
                                    <h5 className="facility-item__title">{item.title}</h5>
                                    <p className="facility-item__desc">{item.content}</p>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
