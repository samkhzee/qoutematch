function HowWorkArrow() {
    return (
        <svg viewBox="0 0 90 36" width="90" height="36" aria-hidden="true" className="how-work-arrow">
            <path
                d="M4 28 C28 4 52 4 78 18"
                fill="none"
                stroke="#2563eb"
                strokeWidth="5"
                strokeLinecap="round"
            />
            <path
                d="M62 10 L80 18 L62 26"
                fill="none"
                stroke="#2563eb"
                strokeWidth="5"
                strokeLinecap="round"
                strokeLinejoin="round"
            />
        </svg>
    );
}

export default function HowWorkSection({ data }) {
    return (
        <div className="how-wowrk-section my-120">
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-lg-10">
                        <div className="section-heading two">
                            <h2 className="section-heading__title s-highlight" data-s-break="-2" data-s-length="2">{data.heading}</h2>
                            <p className="section-heading__desc">{data.subheading}</p>
                        </div>
                    </div>
                </div>
                <div className="row gy-4">
                    {data.elements?.map((item, index) => (
                        <div key={index} className="col-lg-4 col-md-6">
                            <div className="how-work-item">
                                <span className="how-work-item__icon" dangerouslySetInnerHTML={{ __html: item.icon }} />
                                <div className="how-work-item__content">
                                    <h5 className="how-work-item__title">{item.title}</h5>
                                    <p className="how-work-item__desc">{item.content}</p>
                                </div>
                                <div className="how-work-item__shape"><HowWorkArrow /></div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
