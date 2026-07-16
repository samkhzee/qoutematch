export default function RequestDataSummary({ fields, title = 'Request Details' }) {
    if (!fields?.length) {
        return null;
    }

    return (
        <div className="request-new-data-summary mt-4">
            <h6 className="mb-3">{title}</h6>
            <div className="row gy-3">
                {fields.map((field) => (
                    <div key={field.name} className="col-md-6">
                        <div className="request-data-item">
                            <span className="request-data-item__label">{field.name}</span>
                            {field.isFile ? (
                                <a href={field.value} className="request-data-item__value" target="_blank" rel="noreferrer">
                                    <i className="las la-download"></i> Download file
                                </a>
                            ) : (
                                <span className="request-data-item__value">{field.value}</span>
                            )}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
