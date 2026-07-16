import { Link, usePage } from '@inertiajs/react';

export default function Breadcrumb({ pageTitle, customPageTitle, customSubPageTitle, toRoute }) {
    const { routes } = usePage().props;
    const title = customPageTitle || pageTitle;

    return (
        <section className="breadcrumb breadcrumb-section bg-img">
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-lg-12">
                        <div className="breadcrumb__wrapper">
                            <h3 className="breadcrumb__title">{title}</h3>
                            <ul className="breadcrumb__list">
                                <li className="breadcrumb__item">
                                    <Link href={routes.home} className="breadcrumb__link">Home</Link>
                                </li>
                                {customSubPageTitle && (
                                    <>
                                        <li className="breadcrumb__item"><i className="fas fa-angle-right"></i></li>
                                        <li className="breadcrumb__item">
                                            <Link href={toRoute} className="breadcrumb__link">{customSubPageTitle}</Link>
                                        </li>
                                    </>
                                )}
                                <li className="breadcrumb__item"><i className="fas fa-angle-right"></i></li>
                                <li className="breadcrumb__item">
                                    <span className="breadcrumb__item-text">{title}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
