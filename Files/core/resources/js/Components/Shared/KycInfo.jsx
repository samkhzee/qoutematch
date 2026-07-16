export default function KycInfo({ items }) {
    if (!items.length) {
        return <p className="text-muted text-center py-4">KYC data not found.</p>;
    }

    return (
        <ul className="list-group">
            {items.map((item, index) => (
                <li key={index} className="list-group-item d-flex justify-content-between align-items-center gap-3">
                    <span>{item.name}</span>
                    <span>
                        {item.type === 'file' && item.fileUrl ? (
                            <a href={item.fileUrl}><i className="fa-regular fa-file" /> Attachment</a>
                        ) : (
                            item.display
                        )}
                    </span>
                </li>
            ))}
        </ul>
    );
}
