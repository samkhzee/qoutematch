@php
    $content = getContent('facility.content', true)->data_values;
    $facilityElement = getContent('facility.element', false, 4, true);
@endphp

<div class="facility-section my-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-heading two">
                    <h2 class="section-heading__title s-highlight" data-s-break="1" data-s-length="1">
                        {{ __(@$content->heading) }}</h2>
                    <p class="section-heading__desc">{{ __(@$content->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row gy-4 align-items-center">
            <div class="col-lg-6 pe-lg-5">
                <div class="facility-wrapper">
                    @forelse ($facilityElement as $facility)
                        <div class="facility-item">
                            <h5 class="facility-item__title">
                                <span class="facility-item__icon"> <i class="las la-check"></i> </span>
                                {{ __(@$facility->data_values->title) }}
                            </h5>
                            <p class="facility-item__desc">
                                {{ __(@$facility->data_values->content) }}
                            </p>
                        </div>
                    @empty
                        @include('Template::partials.empty', ['message' => 'Facilities not found!'])
                    @endforelse
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <div class="facility-thumb">
                    <img src="{{ frontendImage('facility', @$content->image, '1200x1200') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
