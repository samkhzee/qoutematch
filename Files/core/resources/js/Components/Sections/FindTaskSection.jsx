export default function FindTaskSection({ data }) {
    return (
        <div className="find-task-section my-120">
            <div className="container">
                <div className="row gy-4 align-items-center">
                    <div className="col-lg-6">
                        <div className="section-heading two">
                            <h2 className="section-heading__title">{data.heading}</h2>
                            <p className="section-heading__desc">{data.subheading}</p>
                        </div>
                        <a href={data.buttonUrl} className="btn btn--base">{data.buttonText}</a>
                    </div>
                    <div className="col-lg-6">
                        <img src={data.image} className="w-100" alt="" />
                    </div>
                </div>
            </div>
        </div>
    );
}
