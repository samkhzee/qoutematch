export default function CompletionWorkSection({ data }) {
    return (
        <div className="work-completion my-120">
            <div className="container">
                <div className="work-completion-wrapper">
                    <div className="work-completion-content highlight">
                        <h5 className="work-completion-content__subtitle text--base">{data.heading}</h5>
                        <h2
                            className="work-completion-content__title s-highlight"
                            data-s-break="-1"
                            data-s-length="1"
                        >
                            {data.subheading}
                        </h2>
                        {!!data.steps?.length && (
                            <ul className="list">
                                {data.steps.map((step, index) => (
                                    <li key={index} className="list-item">{step}</li>
                                ))}
                            </ul>
                        )}
                    </div>
                    {data.image && (
                        <div className="work-completion-thumb">
                            <img src={data.image} alt="" />
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
