@php
    $content = getContent('why_choose.content', true)->data_values;
    $whyChooseElement = getContent('why_choose.element', false, 8, true);
@endphp

<div class="why-choose-section my-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-heading two">
                    <h2 class="section-heading__title s-highlight" data-s-break="-2" data-s-length="2">
                        {{ __(@$content->heading) }} </h2>
                    <p class="section-heading__desc">{{ @$content->subheading }}</p>
                </div>
            </div>
        </div>

        @if (!blank($whyChooseElement))
            <div class="choose-wrapper">
                @foreach ($whyChooseElement as $item)
                    <div class="choose-item">
                        <span class="choose-item__icon">
                            <img src="{{ frontendImage('why_choose', @$item->data_values->image, '80x80') }}"
                                class="choose us" alt="">
                        </span>
                        <div class="choose-item__content">
                            <h5 class="choose-item__title"> {{ __(@$item->data_values->title) }} </h5>
                            <p class="choose-item__desc">{{ __(@$item->data_values->content) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="d-flex flex-column justify-content-center align-items-center ">
                <div class="text-center">
                    <img src="{{ asset(activeTemplate(true) . 'images/empty.png') }}" alt="empty" class="img-fluid">
                    <h6 class="text-muted mt-3">@lang('Choose us data not found')</h6>
                </div>
            </div>
        @endif

    </div>
</div>
