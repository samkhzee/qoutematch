export default function WizardOptionCard({
    label,
    description,
    selected = false,
    multi = false,
    onClick,
    disabled = false,
}) {
    return (
        <button
            type="button"
            className={`job-wizard-card${selected ? ' is-selected' : ''}${multi ? ' is-multi' : ''}`}
            onClick={onClick}
            disabled={disabled}
            aria-pressed={selected}
        >
            <span className="job-wizard-card__indicator" aria-hidden="true">
                {multi ? (
                    <i className={`las ${selected ? 'la-check-square' : 'la-square'}`} />
                ) : (
                    <i className={`las ${selected ? 'la-dot-circle' : 'la-circle'}`} />
                )}
            </span>
            <span className="job-wizard-card__content">
                <span className="job-wizard-card__label">{label}</span>
                {description && <span className="job-wizard-card__desc">{description}</span>}
            </span>
        </button>
    );
}
