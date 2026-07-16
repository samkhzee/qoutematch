@php
    $content = getContent('faq.content', true)->data_values;
    $faqElement = getContent('faq.element', false, null, true);
@endphp

<div class="faq-section my-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading two">
                    <h2 class="section-heading__title s-highlight" data-s-break="-2" data-s-length="2">
                        {{ __(@$content->heading) }}</h2>
                    <p class="section-heading__desc"> {{ __(@$content->subheading) }} </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center align-items-center gy-4">
            <div class="col-xxl-7 col-xl-6 pe-xxl-5">
                <div class="accordion accordion-filter custom--accordion" id="accordionExample">
                    @foreach ($faqElement as $item)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $loop->index + 1 }}">
                                <button class="accordion-button {{ !$loop->first ? 'collapsed' : null }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq-{{ $loop->index }}"
                                    aria-expanded="{{ !$loop->first ? 'false' : 'true' }}"
                                    aria-controls="faq-{{ $loop->index }}">
                                    <span class="accordion-button__number"> {{ $loop->index + 1 }} </span>
                                    {{ __(@$item->data_values->question) }}
                                </button>
                            </h2>
                            <div id="faq-{{ $loop->index }}"
                                class="accordion-collapse collapse {{ $loop->first ? 'show' : null }}"
                                aria-labelledby="heading{{ $loop->index + 1 }}"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    {{ __(@$item->data_values->answer) }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="text-end">
                        <button class="load-more-button"> @lang('Show more') </button>
                    </div>
                </div>
            </div>
            <div class="col-xxl-5 col-xl-6  d-xl-block d-none">
                <div class="faq-thumb-wrapper">
                    <div class="faq-thumb">
                        <img src="{{ frontendImage('faq', @$content->image, '840x1140') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
