import { useState } from 'react';

export default function StructuredReviewForm({ dimensions = [], scores, onChange, prefix = 'scores' }) {
    const [values, setValues] = useState(scores ?? {});

    const setScore = (key, value) => {
        const next = { ...values, [key]: value };
        setValues(next);
        onChange?.(next);
    };

    return (
        <div className="structured-review-form">
            {dimensions.map((dimension) => (
                <div key={dimension.key} className="form-group mb-3 structured-review-dimension">
                    <label className="form--label">
                        {dimension.label} <small className="text--danger">*</small>
                    </label>
                    <div className="star-rating structured-review-stars">
                        {[5, 4, 3, 2, 1].map((star) => (
                            <span key={star}>
                                <input
                                    type="radio"
                                    name={`${prefix}[${dimension.key}]`}
                                    value={star}
                                    id={`${prefix}_${dimension.key}_star${star}`}
                                    className="star-input"
                                    checked={Number(values[dimension.key]) === star}
                                    onChange={() => setScore(dimension.key, star)}
                                    required
                                />
                                <label htmlFor={`${prefix}_${dimension.key}_star${star}`}>
                                    <i className="las la-star" />
                                </label>
                            </span>
                        ))}
                    </div>
                </div>
            ))}
        </div>
    );
}
