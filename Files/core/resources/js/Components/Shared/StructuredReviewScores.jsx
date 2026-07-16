export default function StructuredReviewScores({ scores = [], compact = false, className = '' }) {
    if (!scores.length) {
        return null;
    }

    return (
        <div className={`structured-review-scores ${compact ? 'structured-review-scores--compact' : ''} ${className}`.trim()}>
            {scores.map((item) => (
                <div key={item.key} className="structured-review-score-row">
                    <span className="structured-review-score-label">{item.label}</span>
                    <ul className="rating-list structured-review-score-stars mb-0">
                        {[1, 2, 3, 4, 5].map((star) => (
                            <li key={star} className="rating-list__item">
                                <i className={`las la-star ${star <= Math.round(item.average) ? 'text--warning' : 'text-muted'}`}></i>
                            </li>
                        ))}
                    </ul>
                    <span className="structured-review-score-value">{item.average}/5</span>
                </div>
            ))}
        </div>
    );
}
