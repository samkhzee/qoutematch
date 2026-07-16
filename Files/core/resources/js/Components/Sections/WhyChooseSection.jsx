export default function WhyChooseSection({ data }) {
    return (
        <div className="why-choose-section my-120">
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-lg-10">
                        <div className="section-heading two text-center">
                            <h2 className="section-heading__title s-highlight" data-s-break="-2" data-s-length="2">
                                {data.heading}
                            </h2>
                            <p className="section-heading__desc">{data.subheading}</p>
                        </div>
                    </div>
                </div>
                {data.elements?.length > 0 && (
                    <div className="choose-wrapper">
                        {data.elements.map((item, index) => (
                            <div key={index} className="choose-item">
                                <span className="choose-item__icon">
                                    <img src={item.image} alt="" />
                                </span>
                                <div className="choose-item__content">
                                    <h5 className="choose-item__title">{item.title}</h5>
                                    <p className="choose-item__desc">{item.content}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}
