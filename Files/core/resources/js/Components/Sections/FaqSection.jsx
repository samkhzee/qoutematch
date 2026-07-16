export default function FaqSection({ data }) {
    return (
        <div className="faq-section my-120">
            <div className="container">
                <div className="row">
                    <div className="col-lg-12">
                        <div className="section-heading two">
                            <h2 className="section-heading__title s-highlight" data-s-break="-2" data-s-length="2">{data.heading}</h2>
                            <p className="section-heading__desc">{data.subheading}</p>
                        </div>
                    </div>
                </div>
                <div className="row justify-content-center align-items-center gy-4">
                    <div className="col-xxl-7 col-xl-6 pe-xxl-5">
                        <div className="accordion accordion-filter custom--accordion" id="accordionExample">
                            {data.items?.map((item, index) => (
                                <div key={index} className="accordion-item">
                                    <h2 className="accordion-header" id={`heading${index + 1}`}>
                                        <button
                                            className={`accordion-button ${index !== 0 ? 'collapsed' : ''}`}
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target={`#faq-${index}`}
                                        >
                                            <span className="accordion-button__number">{index + 1}</span>
                                            {item.question}
                                        </button>
                                    </h2>
                                    <div id={`faq-${index}`} className={`accordion-collapse collapse ${index === 0 ? 'show' : ''}`}>
                                        <div className="accordion-body">{item.answer}</div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                    <div className="col-xxl-5 col-xl-6 d-xl-block d-none">
                        <div className="faq-thumb-wrapper">
                            <div className="faq-thumb"><img src={data.image} alt="" /></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
