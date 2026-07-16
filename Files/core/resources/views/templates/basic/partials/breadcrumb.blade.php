@php
    $content = getContent('breadcrumb.content', true)->data_values;
@endphp
<section class="breadcrumb breadcrumb-section bg-img" data-background-image="{{ frontendImage('breadcrumb', @$content->image, '1920x205') }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="breadcrumb__wrapper">
                    <h3 class="breadcrumb__title">{{ __(@$customPageTitle) ?? __(@$pageTitle) }}</h3>
                    <ul class="breadcrumb__list">
                        <li class="breadcrumb__item"><a href="{{ route('home') }}" class="breadcrumb__link">
                                @lang('Home')</a>
                        </li>
                        @if (@$customSubPageTitle)
                            <li class="breadcrumb__item"><i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb__item"><a href="{{ @$toRoute }}" class="breadcrumb__link">
                                    {{ __(@$customSubPageTitle) }}</a>
                            </li>
                        @endif
                        <li class="breadcrumb__item"><i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb__item"> <span class="breadcrumb__item-text">
                                {{ __(@$customPageTitle) ?? __(@$pageTitle) }}</span> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
