@php
    $accountContent = getContent('account.content', true)->data_values;
    $contactContent = getContent('contact_us.content', true)->data_values;
    $policyPages = getContent('policy_pages.element', false, null, true);
    $socialIcons = getContent('social_icon.element', orderById: true);
@endphp

<footer class="footer-area">
    <div class="container">
        <div class="footer-area__top">
            <div class="sign-up-wrapper highlight">
                <div class="sign-up-content">
                    <h4 class="sign-up-content__title s-highlight" data-s-break="-1" data-s-length="1">
                        {{ __(@$accountContent->freelancer_title) }}</h4>
                    <p class="sign-up-content__desc"> {{ __(@$accountContent->freelancer_content) }} </p>
                    <a href="{{ route('user.register') }}" class="sign-up-content__btn btn btn--base">
                        {{ __(@$accountContent->freelancer_button_name) }} </a>
                </div>
                <div class="sign-up-content">
                    <h4 class="sign-up-content__title s-highlight" data-s-break="-1" data-s-length="1">
                        {{ __(@$accountContent->buyer_title) }}</h4>
                    <p class="sign-up-content__desc"> {{ __(@$accountContent->buyer_content) }} </p>
                    <a href="{{ route('buyer.register') }}" class="sign-up-content__btn btn btn--base">
                        {{ __(@$accountContent->buyer_button_name) }} </a>
                </div>
            </div>
        </div>
        <div class="footer-wrapper py-60">
            <div class="footer-item">
                <h5 class="footer-item__title"> @lang('Navigation') </h5>
                <ul class="footer-menu">
                    <li class="footer-menu__item"><a href="{{ route('home') }}" class="footer-menu__link">
                            @lang('Home') </a></li>
                    <li class="footer-menu__item"><a href="{{ route('blogs') }}" class="footer-menu__link">
                            @lang('Blogs') </a></li>
                    @foreach ($pages as $k => $data)
                        <li class="footer-menu__item {{ menuActive('pages', null, @$data->slug) }}">
                            <a href="{{ route('pages', $data->slug) }}" class="footer-menu__link">
                                {{ __($data->name) }} </a>
                        </li>
                    @endforeach
                    <li class="footer-menu__item"><a href="{{ route('contact') }}" class="footer-menu__link">@lang('Contact Us') </a></li>

                </ul>
            </div>
            <div class="footer-item">
                <h5 class="footer-item__title"> @lang('Important Link') </h5>
                <ul class="footer-menu">
                    @if (auth()->check())
                        <li class="footer-menu__item"><a href="{{ route('user.home') }}" class="footer-menu__link"> @lang('Dashboard') </a></li>
                    @elseif(auth('buyer')->check())
                        <li class="footer-menu__item"><a href="{{ route('buyer.home') }}" class="footer-menu__link"> @lang('Dashboard') </a></li>
                    @else
                        <li class="footer-menu__item"><a href="{{ route('user.login') }}" class="footer-menu__link"> @lang('Login Now') </a></li>
                    @endif
                    <li class="footer-menu__item"><a href="{{ route('buyer.job.post.details') }}" class="footer-menu__link"> @lang('Post a Job') </a></li>
                    <li class="footer-menu__item"><a href="{{ route('freelance.jobs') }}" class="footer-menu__link">
                            @lang('Find a Jobs') </a>
                    </li>
                    <li class="footer-menu__item"><a href="{{ route('all.freelancers') }}" class="footer-menu__link">@lang('Find a Talent') </a>
                    </li>
                </ul>
            </div>

            <div class="footer-item">
                <h5 class="footer-item__title"> @lang('Terms') </h5>
                <ul class="footer-menu">
                    @foreach ($policyPages as $policy)
                        <li class="footer-menu__item"><a href="{{ route('policy.pages', @$policy->slug) }}" class="footer-menu__link">{{ __(@$policy->data_values->title) }}</a>
                        </li>
                    @endforeach
                    <li class="footer-menu__item"><a href="{{ route('cookie.policy') }}" class="footer-menu__link">
                            @lang('Cookie Policy') </a>
                    </li>
                </ul>
            </div>
            <div class="footer-item">
                <h5 class="footer-item__title"> @lang('Contact Us')</h5>
                <ul class="footer-contact-menu">
                    <li class="footer-contact-menu__item">
                        <div class="footer-contact-menu__item-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="footer-contact-menu__item-content">
                            <p> {{ __(@$contactContent->contact_details) }}</p>
                        </div>
                    </li>
                    <li class="footer-contact-menu__item">
                        <div class="footer-contact-menu__item-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="footer-contact-menu__item-content">
                            <a title="@lang('Call us')" href="tel:{{ @$contactContent->contact_number }}">{{ __(@$contactContent->contact_number) }}</a>
                        </div>
                    </li>
                    <li class="footer-contact-menu__item">
                        <div class="footer-contact-menu__item-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="footer-contact-menu__item-content">
                            <a title="@lang('E-mail us')" href="mailto:{{ @$contactContent->email_address }}">{{ __(@$contactContent->email_address) }}</a>
                        </div>
                    </li>
                </ul>
                <div class="social-list-wrapper">
                    <p class="title">@lang('Follow Us') </p>
                    <ul class="social-list">
                        @foreach ($socialIcons as $social)
                            <li class="social-list__item"><a href="{{ @$social->data_values->url }}" target="_blank" title="{{ __(@$social->data_values->title) }}" class="social-list__link flex-center">@php echo $social->data_values->social_icon @endphp</a> </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- bottom Footer -->
    <div class="bottom-footer py-3">
        <div class="container">
            <div class="row gy-3">
                <div class="col-md-12 text-center">
                    <div class="bottom-footer-text"> @lang('Copyright') &copy;{{ date('Y') }}
                        <a href="{{ route('home') }}">{{ __(gs('site_name')) }}</a> @lang('All rights reserved') .
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- ========
