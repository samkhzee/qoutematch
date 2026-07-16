import { Link } from '@inertiajs/react';

export default function BlogSection({ data }) {
    return (
        <div className="blog-section my-120">
            <div className="container">
                <div className="section-heading two">
                    <h2 className="section-heading__title s-highlight" data-s-break="-1" data-s-length="1">{data.heading}</h2>
                    <p className="section-heading__desc">{data.subheading}</p>
                </div>
                <div className="row gy-4">
                    {data.items?.map((blog) => (
                        <div key={blog.slug} className="col-xl-4 col-sm-6">
                            <Link href={blog.url} className="blog-item">
                                <div className="blog-item__thumb"><img src={blog.image} className="fit-image" alt="" /></div>
                                <div className="blog-item__content">
                                    <h6 className="blog-item__title">{blog.title}</h6>
                                    <ul className="text-list flex-align">
                                        <li className="text-list__item">{blog.date}</li>
                                    </ul>
                                </div>
                            </Link>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
