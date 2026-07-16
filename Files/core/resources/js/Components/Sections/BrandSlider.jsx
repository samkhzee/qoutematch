import Slider from 'react-slick';

const brandSliderSettings = {
    slidesToShow: 5,
    slidesToScroll: 1,
    speed: 2000,
    cssEase: 'linear',
    autoplay: true,
    autoplaySpeed: 0,
    pauseOnHover: true,
    pauseOnFocus: true,
    arrows: false,
    dots: false,
    infinite: true,
    responsive: [
        { breakpoint: 1199, settings: { slidesToShow: 4 } },
        { breakpoint: 767, settings: { slidesToShow: 3 } },
        { breakpoint: 575, settings: { slidesToShow: 2 } },
    ],
};

export default function BrandSlider({ clients = [] }) {
    if (!clients.length) return null;

    return (
        <Slider className="brand-slider" {...brandSliderSettings}>
            {clients.map((client, index) => (
                <div key={index}>
                    <img src={client.image} alt="" />
                </div>
            ))}
        </Slider>
    );
}
