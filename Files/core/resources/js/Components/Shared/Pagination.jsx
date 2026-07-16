import { Link, usePage } from '@inertiajs/react';

export default function Pagination({ links = [] }) {
    if (!links.length) return null;

    return (
        <nav aria-label="Page navigation">
            <ul className="pagination justify-content-center">
                {links.map((link, index) => (
                    <li
                        key={index}
                        className={`page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''}`}
                    >
                        {link.url ? (
                            <Link className="page-link" href={link.url} dangerouslySetInnerHTML={{ __html: link.label }} />
                        ) : (
                            <span className="page-link" dangerouslySetInnerHTML={{ __html: link.label }} />
                        )}
                    </li>
                ))}
            </ul>
        </nav>
    );
}

export function EmptyState({ message = 'Data not found', image }) {
    const { template } = usePage().props;

    return (
        <div className="d-flex flex-column justify-content-center align-items-center">
            <div className="text-center">
                {image !== false && (
                    <img src={image || `${template.assetPath}images/empty.png`} alt="empty" className="img-fluid" />
                )}
                <h6 className="text-muted mt-3">{message}</h6>
            </div>
        </div>
    );
}
