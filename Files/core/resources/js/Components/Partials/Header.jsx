import { Link, usePage } from '@inertiajs/react';

export default function Header() {
    const { site, navigation, routes, auth } = usePage().props;
    const pages = navigation?.pages || [];
    const usePagesDropdown = pages.length > 1;
    const postJobUrl = auth?.buyer ? routes.buyerJobPost : routes.postJob;

    return (
        <header className="header" id="header">
            <div className="container">
                <nav className="navbar navbar-expand-xl navbar-light">
                    <Link className="navbar-brand logo" href={routes.home}>
                        <img src={site.logo} alt={site.name} />
                    </Link>

                    <div className="d-xl-none d-block job-link">
                        <Link href={postJobUrl} className="btn btn--base btn--sm header-post-job-btn">
                            Post Job
                        </Link>
                    </div>

                    <button
                        className="navbar-toggler header-button"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <span id="hiddenNav">
                            <i className="las la-bars"></i>
                        </span>
                    </button>

                    <div className="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul className="navbar-nav nav-menu me-auto align-items-xl-center">
                            <li className="nav-item">
                                <Link className="nav-link" href={routes.home}>Home</Link>
                            </li>
                            <li className="nav-item">
                                <Link className="nav-link" href={routes.categories}>Categories</Link>
                            </li>
                            <li className="nav-item">
                                <Link className="nav-link" href={routes.freelanceJobs}>Browse Requests</Link>
                            </li>
                            <li className="nav-item">
                                <Link className="nav-link" href={routes.allFreelancers}>Find Providers</Link>
                            </li>
                            <li className="nav-item d-xl-none">
                                <Link className="nav-link fw-semibold text--base" href={postJobUrl}>Post Job</Link>
                            </li>

                            {usePagesDropdown ? (
                                <li className="nav-item dropdown">
                                    <a
                                        className="nav-link"
                                        href="#"
                                        role="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        Pages <span className="nav-item__icon"><i className="las la-angle-down"></i></span>
                                    </a>
                                    <ul className="dropdown-menu">
                                        {pages.map((page) => (
                                            <li key={page.id} className="dropdown-menu__list">
                                                <Link href={`/${page.slug}`} className="dropdown-item dropdown-menu__link">
                                                    {page.name}
                                                </Link>
                                            </li>
                                        ))}
                                    </ul>
                                </li>
                            ) : (
                                pages.map((page) => (
                                    <li key={page.id} className="nav-item">
                                        <Link className="nav-link" href={`/${page.slug}`}>{page.name}</Link>
                                    </li>
                                ))
                            )}

                            <li className="nav-item">
                                <Link className="nav-link" href={routes.blogs}>Blogs</Link>
                            </li>
                            <li className="nav-item">
                                <Link className="nav-link" href={routes.contact}>Contact</Link>
                            </li>

                            <li className="nav-item d-flex justify-content-between w-100 d-xl-none">
                                <div className="top-button w-100">
                                    <ul className="login-registration-list d-flex align-items-center flex-wrap gap-3 mb-0">
                                        <li className="login-registration-list__item d-flex gap-3">
                                            {auth?.user ? (
                                                <Link href={routes.userHome} className="login-registration-list__link">Provider Dashboard</Link>
                                            ) : auth?.buyer ? (
                                                <Link href={routes.buyerHome} className="login-registration-list__link">Customer Dashboard</Link>
                                            ) : (
                                                <>
                                                    <Link href={routes.buyerLogin} className="login-registration-list__link">Customer Login</Link>
                                                    <Link href={routes.userLogin} className="login-registration-list__link">Provider Login</Link>
                                                </>
                                            )}
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div className="d-xl-block d-none header-actions">
                        <div className="top-button d-flex align-items-center">
                            <ul className="login-registration-list d-flex align-items-center flex-nowrap mb-0">
                                {auth?.user ? (
                                    <li className="login-registration-list__item">
                                        <Link href={routes.userHome} className="login-registration-list__link">Provider Dashboard</Link>
                                    </li>
                                ) : auth?.buyer ? (
                                    <li className="login-registration-list__item">
                                        <Link href={routes.buyerHome} className="login-registration-list__link">Customer Dashboard</Link>
                                    </li>
                                ) : (
                                    <>
                                        <li className="login-registration-list__item">
                                            <Link href={routes.buyerLogin} className="login-registration-list__link">Customer Login</Link>
                                        </li>
                                        <li className="login-registration-list__item">
                                            <Link href={routes.userLogin} className="login-registration-list__link">Provider Login</Link>
                                        </li>
                                    </>
                                )}

                                <li className="login-registration-list__item">
                                    <Link href={postJobUrl} className="btn btn--base header-post-job-btn">Post Job</Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
    );
}
