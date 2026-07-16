import { Link, usePage } from '@inertiajs/react';
import FrontendLayout from '@/Components/Layout/FrontendLayout';

export default function CategoryDetail({ pageTitle, seo, category, locations = [] }) {
    const { routes } = usePage().props;

    return (
        <FrontendLayout
            pageTitle={pageTitle}
            seo={seo}
            customSubPageTitle="Categories"
            toRoute={routes.categories}
        >
            <section className="pb-120">
                <div className="container">
                    <div className="row gy-4 align-items-start">
                        <div className="col-lg-5">
                            {category.image && (
                                <div className="category-detail__thumb mb-4">
                                    <img src={category.image} alt="" />
                                </div>
                            )}
                            {category.description && (
                                <p className="section-heading__desc mb-4">{category.description}</p>
                            )}
                            <div className="d-flex flex-wrap gap-2">
                                <Link href={category.postUrl} className="btn btn--base">
                                    Get Quotes
                                </Link>
                                <Link href={category.jobsUrl} className="btn btn-outline--base">
                                    Browse Requests ({category.jobsCount})
                                </Link>
                            </div>
                            <p className="mt-3 mb-0">
                                <Link href={routes.categories} className="text--base">
                                    ← All categories
                                </Link>
                            </p>
                        </div>

                        <div className="col-lg-7">
                            <h2 className="h4 mb-4">Subcategories</h2>
                            <div className="row gy-3">
                                {category.subcategories.map((sub) => (
                                    <div key={sub.id} className="col-md-6">
                                        <div className="subcategory-card h-100">
                                            <h3 className="subcategory-card__title">{sub.name}</h3>
                                            {sub.description && (
                                                <p className="subcategory-card__desc">{sub.description}</p>
                                            )}
                                            <div className="d-flex flex-wrap gap-2 mt-auto pt-3">
                                                <Link href={sub.postUrl} className="btn btn--base btn--sm">
                                                    Get Quotes
                                                </Link>
                                                <Link href={sub.jobsUrl} className="btn btn-outline--base btn--sm">
                                                    Browse
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {locations.length > 0 && (
                        <div className="row mt-5">
                            <div className="col-12">
                                <h2 className="h4 mb-3">Get quotes near you</h2>
                                <div className="d-flex flex-wrap gap-2">
                                    {locations.map((location) => (
                                        <Link
                                            key={location.id}
                                            href={location.serviceUrl}
                                            className="category-tag"
                                        >
                                            {category.name} in {location.name}
                                        </Link>
                                    ))}
                                </div>
                                <p className="mt-3 mb-0">
                                    <Link href={routes.locations} className="text--base">
                                        View all locations →
                                    </Link>
                                </p>
                            </div>
                        </div>
                    )}
                </div>
            </section>
        </FrontendLayout>
    );
}
