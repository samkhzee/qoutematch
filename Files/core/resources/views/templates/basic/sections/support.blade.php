@php
    $support = getContent('support.content', true)->data_values;
    $content = getContent('brand.content', true)->data_values;
    $brandElement = getContent('brand.element', false, null, true);
@endphp
<div class="support-section my-120">
    <div class="container">
        <div class="support-wrapper">
            <div class="support-wrapper__left">
                <h6 class="title">{{ __(@$content->heading) }}</h6>
                <div class="company-list">
                    @foreach ($brandElement as $brand)
                        <div class="company-name">
                            <div class="thumb">
                                <img src="{{ frontendImage('brand', @$brand->data_values->image, '260x60') }}"
                                    alt="">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="support-wrapper__right">
                <div class="support-content">
                    <h4 class="support-content__title">{{ __(@$support->heading) }}</h4>
                    <p class="support-item">
                        <span class="support-item__icon"> <i class="las fa-phone"></i> </span>
                        @lang('Hot Line'): {{ @$support->hotline_number }}
                    </p>
                    <p class="support-item">
                        <span class="support-item__icon"> <i class="las la-paper-plane"></i> </span>
                        @lang('Email'): {{ @$support->hotline_email }}
                    </p>
                </div>
                <div class="support-wrapper__shape">
                    <img src="{{ frontendImage('support',@$support->shape , '550x600') }}" alt="">
                </div>
                <div class="support-wrapper__thumb">
                    <img src="{{ frontendImage('support',@$support->image , '275x300') }}" alt="">
                </div>
                
            </div>
        </div>
    </div>
</div>
