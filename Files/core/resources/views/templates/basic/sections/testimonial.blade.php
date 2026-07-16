@php
    $content = getContent('testimonial.content', true)->data_values;
    $testimonialElement = getContent('testimonial.element', false, null, true);
@endphp

<section class="testimonials my-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-heading two">
                    <h2 class="section-heading__title s-highlight" data-s-break="-1" data-s-length="1">
                        {{ __(@$content->heading) }}
                    </h2>
                    <p class="section-heading__desc">{{ __(@$content->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="testimonial-slider">
            @foreach ($testimonialElement as $item)
                <div class="testimonails-card">
                    <div class="testimonial-item">
                        <span class="testimonial-item__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 43 33" fill="none">
                                <path d="M0 0.612305V32.6123L16 16.6123V0.612305H0Z" />
                                <path d="M26.6666 0.612305V32.6123L42.6666 16.6123V0.612305H26.6666Z" />
                            </svg>
                        </span>
                        <p class="testimonial-item__desc">
                            {{ __(@$item->data_values->quote) }}
                        </p>
                        <div class="testimonial-item__info">
                            <div class="testimonial-item__thumb">
                                <img src="{{ frontendImage('testimonial', @$item->data_values->image, '140x140') }}"
                                    class="fit-image" alt="">
                            </div>
                            <div class="testimonial-item__details">
                                <h6 class="testimonial-item__name">{{ __(@$item->data_values->name) }}</h6>
                                <span class="testimonial-item__designation"> @lang('From')
                                    {{ __(@$item->data_values->country) }} </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
