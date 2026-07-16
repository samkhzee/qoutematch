export default function SupportSection({ data }) {
    return (
        <div className="support-section my-120">
            <div className="container">
                <div className="row gy-4 align-items-center">
                    <div className="col-lg-6">
                        <div className="section-heading two">
                            <h2 className="section-heading__title">{data.heading}</h2>
                            <p className="section-heading__desc">{data.subheading}</p>
                        </div>
                    </div>
                    <div className="col-lg-6">
                        <img src={data.image} className="w-100" alt="" />
                    </div>
                </div>
            </div>
        </div>
    );
}
