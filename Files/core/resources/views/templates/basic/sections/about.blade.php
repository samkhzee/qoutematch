@php
    $content = getContent('about.content', true)->data_values;
    $aboutElement = getContent('about.element', false, 4, true);
@endphp

<div class="about-section my-120">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-lg-5 pe-xl-5">
                <div class="section-heading two">
                    <h4 class="section-heading__title text-start">
                        {{ __(@$content->heading) }}
                    </h4>
                </div>
                <div class="about-wrapper">

                    @forelse ($aboutElement as $about)
                        <div class="about-item">
                            <span class="about-item__icon">
                                <img src="{{ frontendImage('about', @$about->data_values->image, '25x25') }}"
                                    alt="">
                            </span>
                            <div class="about-item__content">
                                <h5 class="about-item__title">
                                    {{ __(@$about->data_values->title) }}
                                </h5>
                                <p class="about-item__desc">
                                    {{ __(@$about->data_values->content) }}
                                </p>
                            </div>
                        </div>
                    @empty
                        @include('Template::partials.empty', ['message' => 'Done work not found!'])
                    @endforelse
                </div>
            </div>
            <div class="col-lg-7">
                <div class="about-thumb-wrapper">
                    <div class="about-thumb-wrapper__shape">
                        <img src="{{ asset(activeTemplate(true) . '/shape/about-shape.png') }}" alt="">
                    </div>
                    <div class="about-thumb-wrapper__thumb">
                        <img src="{{ frontendImage('about', @$content->image, '640x880') }}" alt="">
                        <div class="shape-one">
                        </div>
                        <div class="shape-two">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
