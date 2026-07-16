const categorySliderOptions = {
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 1000,
    pauseOnHover: true,
    speed: 2000,
    dots: false,
    arrows: false,
    responsive: [
        { breakpoint: 1199, settings: { slidesToShow: 3 } },
        { breakpoint: 767, settings: { slidesToShow: 3, arrows: false } },
        { breakpoint: 575, settings: { slidesToShow: 2, arrows: false } },
    ],
};

const sliderSelectors = [
    { selector: '.category-slider', options: categorySliderOptions },
];

function initSlider(selector, options) {
    const $ = window.jQuery;
    if (!$?.fn?.slick) return false;

    let initialized = false;

    $(selector).each(function initEach() {
        const $el = $(this);
        if (!$el.children().length) return;

        if ($el.hasClass('slick-initialized')) {
            $el.slick('unslick');
        }

        $el.slick(options);
        initialized = true;
    });

    return initialized;
}

export function initTemplateSliders() {
    sliderSelectors.forEach(({ selector, options }) => {
        initSlider(selector, options);
    });
}

export function slidersNeedInit() {
    const $ = window.jQuery;
    if (!$?.fn?.slick) return true;

    return sliderSelectors.some(({ selector }) => {
        const $el = $(selector).first();
        return $el.length && $el.children().length && !$el.hasClass('slick-initialized');
    });
}

export function destroyTemplateSliders() {
    const $ = window.jQuery;
    if (!$?.fn?.slick) return;

    sliderSelectors.forEach(({ selector }) => {
        $(selector).each(function destroyEach() {
            const $el = $(this);
            if ($el.hasClass('slick-initialized')) {
                $el.slick('unslick');
            }
        });
    });
}
