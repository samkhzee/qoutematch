import Slider from 'react-slick';
import { FreelancerCard } from '@/Components/Sections/SectionRenderer';

const bestFreelancerSliderSettings = {
    slidesToShow: 4,
    slidesToScroll: 1,
    speed: 2000,
    adaptiveHeight: false,
    pauseOnDotsHover: false,
    pauseOnHover: true,
    pauseOnFocus: true,
    dots: false,
    arrows: true,
    prevArrow: (
        <button type="button" className="slick-prev">
            <i className="las la-angle-left"></i>
        </button>
    ),
    nextArrow: (
        <button type="button" className="slick-next">
            <i className="las la-angle-right"></i>
        </button>
    ),
    responsive: [
        { breakpoint: 1199, settings: { slidesToShow: 3 } },
        { breakpoint: 991, settings: { slidesToShow: 2 } },
        { breakpoint: 767, settings: { slidesToShow: 2, arrows: false, dots: true } },
        { breakpoint: 575, settings: { slidesToShow: 1, arrows: false, dots: true } },
    ],
};

export default function TopFreelancerSection({ data }) {
    return (
        <div className="best-freelancer-section py-120 my-120">
            <div className="container">
                <div className="row">
                    <div className="col-lg-12">
                        <div className="section-heading style-left highlight">
                            <h2 className="section-heading__title s-highlight" data-s-break="-1" data-s-length="1">
                                {data.heading}
                            </h2>
                            <p className="section-heading__desc">{data.subheading}</p>
                        </div>
                    </div>
                </div>
                {!!data.freelancers?.length && (
                    <Slider className="best-freelancer" {...bestFreelancerSliderSettings}>
                        {data.freelancers.map((freelancer) => (
                            <div key={freelancer.username}>
                                <FreelancerCard freelancer={freelancer} />
                            </div>
                        ))}
                    </Slider>
                )}
                <div className="counter-up-wrapper">
                    <div className="counterup-item">
                        {data.counters?.map((counter, index) => (
                            <div key={index} className="counterup-item__content">
                                <div className="counterup-wrapper">
                                    <span className="counterup-item__icon" dangerouslySetInnerHTML={{ __html: counter.icon }} />
                                    <div className="content">
                                        <div className="counterup-item__number">
                                            <h5 className="counterup-item__title">
                                                <span className="odometer" data-odometer-final={counter.digit}></span> {counter.suffix}
                                            </h5>
                                        </div>
                                        <span className="counterup-item__text mb-0">{counter.content}</span>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}
