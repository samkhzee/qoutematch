@extends('Template::layouts.frontend')
@section('content')
    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
        $banner = getContent('banner.content', true)->data_values;
    @endphp
    <div class="profile-section mt-60 mb-120">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="profile-wrapper">
                        <div class="profile-wrapper__profile">
                            <div class="profile-thumb">
                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $freelancer->image, avatar: true) }}"
                                    alt="">
                            </div>
                            <div class="main-content-wrapper">
                                <div class="profile-content">
                                    <h5 class="profile-content__name d-flex align-items-center flex-wrap gap-2">
                                        {{ @$freelancer->fullname }}
                                        @include('Template::partials.verification_badges', ['user' => $freelancer])
                                    </h5>
                                    <span class="profile-content__title"> {{ __($freelancer->tagline) }} </span>
                                    <ul class="rating-list">
                                        @php echo avgRating($freelancer->avg_rating); @endphp
                                        <li class="rating-list__number"> ({{ $freelancer->avg_rating }}) </li>
                                    </ul>
                                    <div class="profile-content__info">
                                        <div class="info-item">
                                            <span class="info-item__thumb">
                                                <img src="{{ asset(activeTemplate(true) . '/icons/check.png') }}"
                                                    alt="">
                                            </span>
                                            <p class="info-item__text">
                                                {{ showAmount($freelancerSuccessJobPercent, currencyFormat: false) }}%
                                                @lang('Job Success') </p>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-item__thumb">
                                                <img src="{{ asset(activeTemplate(true) . '/icons/thumb.png') }}"
                                                    alt="">
                                            </span>
                                            <p class="info-item__text"> {{ $successfulJobs }} @lang('Complete Job') </p>
                                        </div>
                                        @if ($freelancer->badge)
                                            <div class="info-item">
                                                <span class="info-item__thumb">
                                                    <img src="{{ asset(activeTemplate(true) . '/icons/top-rated.png') }}"
                                                        alt="">
                                                </span>
                                                <p class="info-item__text">{{ __(@$freelancer->badge->badge_name) }}
                                                    @lang('Level')</p>
                                            </div>
                                        @endif
                                        <div class="info-item">
                                            <span class="info-item__thumb">
                                                <img src="{{ asset(activeTemplate(true) . '/icons/location.png') }}"
                                                    alt="">
                                            </span>
                                            <p class="info-item__text"> {{ @$freelancer->city . ',' ?? '' }}
                                                {{ @$freelancer->country_name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-action-btn">
                                    @if ($freelancer->badge)
                                        <div class="profile-badge">
                                            <img data-bs-toggle="tooltip" title="{{ __($freelancer->badge->badge_name) }}"
                                                src="{{ getImage(getFilePath('badge') . '/' . $freelancer->badge->image, getFileSize('badge')) }}"
                                                alt="">
                                        </div>
                                    @endif
                                    @if (auth()->guard('buyer')->check())
                                        <button class="profile-action-btn__bid btn btn--sm confirmationBtn"
                                            data-question="@lang('Are you sure to invite the :freeName freelancer, to bid your jobs?', ['freeName' => $freelancer->fullname])"
                                            data-action="{{ route('buyer.talent.invite', $freelancer->id) }}">
                                            @lang('Invite to bid') </button>
                                    @else
                                        <a href="{{ route('buyer.login') }}" class="profile-action-btn__bid btn btn--sm">
                                            @lang('Invite to bid') </a>
                                    @endif
                                    <ul class="nav-menu">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link" href="javascript:void(0)" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <button class="profile-action-btn__share" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa-solid fa-share-nodes"></i>
                                                </button>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="dropdown-menu__list">
                                                    <div class="copy-link input-group">
                                                        <input class="form-control form--control copy-input fs-12"
                                                            type="text" value="{{ url()->current() }}" aria-label=""
                                                            disabled>
                                                        <span class="input-group-text flex-align copy-btn fs-12 "
                                                            id="copyBtn" data-link="{{ url()->current() }}"><i
                                                                class="far fa-copy"></i></span>
                                                    </div>
                                                </li>
                                                <li class="dropdown-menu__list">
                                                    <a class="social-btn facebook flex-align"
                                                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                                        target="_blank"><i class="fab fa-facebook-f"></i>
                                                        @lang('Facebook')</a>
                                                </li>
                                                <li class="dropdown-menu__list">
                                                    <a class="social-btn linkedin flex-align"
                                                        href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $freelancer->fullname }}&amp;summary={{ $freelancer->fullname }}"
                                                        target="_blank"><i
                                                            class="fab fa-linkedin-in"></i>@lang('Linkedin')</a>
                                                </li>
                                                <li class="dropdown-menu__list">
                                                    <a class="social-btn twitter flex-align"
                                                        href="https://twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"
                                                        target="_blank"><i class="fab fa-twitter"></i>@lang('Twitter')</a>
                                                </li>
                                                <li class="dropdown-menu__list">
                                                    <a class="social-btn instagram flex-align"
                                                        href="https://www.instagram.com/share?url={{ urlencode(url()->current()) }}"
                                                        target="_blank"><i
                                                            class="fab fa-instagram"></i>@lang('Instagram')</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="profile-wrapper__body">
                            <div class="body-content">
                                <h6 class="body-content__title">@lang(' Why should you work with me ?') </h6>
                                <p class="body-content__desc"> @php echo @$freelancer->about @endphp</p>
                                <div class="proficiency-wrapper">
                                    <div class="proficiency-wrapper__item">
                                        <p class="proficiency-wrapper__title"> @lang('My Specializations') </p>
                                        <ul class="proficiency-list">
                                            @foreach ($freelancerSkills as $skill)
                                                <li class="proficiency-list__item">{{ __($skill->name) }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="review-wrapper">
                                <h6 class="review-content__title"> @lang('Recent Reviews') </h6>
                                <div class="review-content-container">
                                    @forelse ($freelancersReviews ?? [] as $review)
                                        <div class="review-content">
                                            <p class="review-content__name"> {{ __(@$review->buyer->fullname) }}</p>
                                            <span class="review-content__address"> @lang('From')
                                                {{ __($review->buyer->country_name) }} </span>
                                            <ul class="review-rating-list">
                                                @for ($i = 0; $i < min($review->rating, 5); $i++)
                                                    <li class="review-rating-list__item"> <i class="las la-star"></i>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <p class="review-content__desc">
                                                {{ __(@$review->review) }}
                                            </p>
                                        </div>
                                    @empty
                                        <div class="empty-message text-center mb-3">
                                            <img src="{{ asset(activeTemplate(true) . 'images/empty.png') }}"
                                                alt="empty">
                                            <h6 class="text-muted mt-3">@lang('No recent reviews!')</h6>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            @if ($freelancersReviews->hasPages())
                                <div class="px-4 pb-3">
                                    {{ paginateLinks($freelancersReviews) }}
                                </div>
                            @endif

                            @php
                                $hasPortfolios = $freelancer->portfolios->where('status', Status::ENABLE);
                            @endphp
                            @if (@$hasPortfolios)
                                <div class="portfolio">
                                    <h6 class="portfolio__title"> @lang('My Portfolio') </h6>
                                    <div class="portfolio-wrapper">
                                        @foreach ($hasPortfolios as $portfolio)
                                            <div class="portfolio-item">
                                                <div class="portfolio-item__thumb">
                                                    <img src="{{ getImage(getFilePath('portfolio') . '/' . $portfolio->image, getFileSize('portfolio')) }}"
                                                        alt="">
                                                </div>
                                                <div class="portfolio-item__content">
                                                    <h6 class="portfolio-item__title">
                                                        <span
                                                            class="portfolio-item__title-link">{{ __($portfolio->title) }}</span>
                                                    </h6>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="profile-wrapper__bottom">
                            <h6 class="title"> @lang('Freelancer Similar Skills')</h6>
                            <div class="row gy-4 justify-content-center">
                                @forelse ($similarFreelancers as $user)
                                    <div class="col-xl-4 col-sm-6">
                                        <div class="freelancer-item">
                                            <div class="freelancer-item__thumb">
                                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}"
                                                    alt="">
                                            </div>
                                            <div class="freelancer-item__content">
                                                <h6 class="freelancer-item__name"> {{ __($user->fullname) }} </h6>
                                                @if ($user->badge)
                                                    <small class="text--base">
                                                        <i class="las la-award"></i>
                                                        {{ __($user->badge->badge_name) }}
                                                    </small>
                                                @endif
                                                <span class="freelancer-item__designation">
                                                    {{ __(strLimit($user->tagline, 45)) }}
                                                </span>
                                                <ul class="text-list">
                                                    <li class="text-list__item">
                                                        @php echo  avgRating($user->avg_rating) @endphp
                                                    </li>
                                                    <li class="text-list__item">
                                                        ({{ $user->avg_rating }})
                                                    </li>
                                                </ul>
                                                <ul class="skill-list">
                                                    @foreach ($user->skills as $skill)
                                                        <li class="skill-list__item">
                                                            <span class="skill-list__link">{{ __($skill->name) }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <div class="freelancer-item__btn">
                                                    <a href="{{ route('talent.explore', $user->username) }}"
                                                        class="btn--base btn btn--sm"> @lang('View Profile')
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-message text-center py-5">
                                        <img src="{{ asset(activeTemplate(true) . 'images/empty.png') }}" alt="empty">
                                        <p class="text-muted mt-3">@lang('No freelancer found!')</p>
                                    </div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!--================== sidebar start here ================== -->
                    <div class="sidebar-wrapper">
                        <div class="sidebar-item">
                            <h6 class="sidebar-item__title"> @lang('Verifications') </h6>
                            <div class="sidebar-item__verify">
                                @if ($freelancer->badge)
                                    <a href="javascript:void(0)" class="verify-item">
                                        <span class="verify-item__icon">
                                            <img data-bs-toggle="tooltip"
                                                title="{{ __($freelancer->badge->badge_name) }}"
                                                src="{{ getImage(getFilePath('badge') . '/' . $freelancer->badge->image, getFileSize('badge')) }}"
                                                alt="">

                                        </span>
                                        <div class="verify-item__content">
                                            <span class="verify-item__title"> @lang('Profile Level') </span>
                                            <p class="verify-item__text">
                                                @lang('Verified level name: :badge ', ['badge' => $freelancer->badge->badge_name])
                                            </p>
                                        </div>
                                    </a>
                                @endif
                                <a href="javascript:void(0)" class="verify-item">
                                    <span class="verify-item__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 24 24"
                                            style="enable-background:new 0 0 512 512" xml:space="preserve"
                                            class="">
                                            <g>
                                                <path
                                                    d="M11.86 9.93h.01l4.9-1.67c.02-.09.02-.18.02-.26a.69.69 0 0 0-.04-.25c-.08-.23-.23-.48-.44-.66V4.9c0-1.62-.58-2.26-1.18-2.63C14.82 1.33 13.53 0 11 0 8 0 5.74 2.97 5.74 4.9c0 .8-.03 1.43-.06 1.91 0 .1-.01.19-.01.27-.22.2-.37.47-.44.73-.01.06-.02.12-.02.19 0 .78.44 1.91.5 2.04.06.17.19.31.36.39.01.04.02.1.02.22 0 1.06.91 2.06 1.41 2.54-.05 1.1-.36 1.86-.8 2.05l-3.92 1.3a3.406 3.406 0 0 0-2.23 2.41l-.53 2.12a.754.754 0 0 0 .73.93h11.21c-.3-.38-.58-.8-.84-1.25a8.51 8.51 0 0 1-1.12-4.2v-4.01c0-1.18.75-2.22 1.86-2.61z"
                                                    opacity="1" data-original="#000000" class=""></path>
                                                <path
                                                    d="m23.491 11.826-5.25-1.786a.737.737 0 0 0-.482 0l-5.25 1.786a.748.748 0 0 0-.509.71v4.018c0 4.904 5.474 7.288 5.707 7.387a.754.754 0 0 0 .586 0c.233-.1 5.707-2.483 5.707-7.387v-4.018a.748.748 0 0 0-.509-.71zm-2.205 3.792-2.75 3.5a1 1 0 0 1-1.437.142l-1.75-1.5a1 1 0 1 1 1.301-1.518l.958.821 2.105-2.679a.998.998 0 0 1 1.404-.168.996.996 0 0 1 .169 1.402z"
                                                    opacity="1" data-original="#000000" class=""></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="verify-item__content">
                                        <span class="verify-item__title"> @lang('Profile Status') </span>
                                        <p class="verify-item__text">
                                            {{ getProfileCompletionBadge($freelancer) }}
                                        </p>
                                    </div>
                                </a>

                                <a href="javascript:void(0)" class="verify-item">
                                    <span class="verify-item__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 24 24"
                                            style="enable-background:new 0 0 512 512" xml:space="preserve"
                                            class="">
                                            <g>
                                                <path
                                                    d="M11.86 9.93h.01l4.9-1.67c.02-.09.02-.18.02-.26a.69.69 0 0 0-.04-.25c-.08-.23-.23-.48-.44-.66V4.9c0-1.62-.58-2.26-1.18-2.63C14.82 1.33 13.53 0 11 0 8 0 5.74 2.97 5.74 4.9c0 .8-.03 1.43-.06 1.91 0 .1-.01.19-.01.27-.22.2-.37.47-.44.73-.01.06-.02.12-.02.19 0 .78.44 1.91.5 2.04.06.17.19.31.36.39.01.04.02.1.02.22 0 1.06.91 2.06 1.41 2.54-.05 1.1-.36 1.86-.8 2.05l-3.92 1.3a3.406 3.406 0 0 0-2.23 2.41l-.53 2.12a.754.754 0 0 0 .73.93h11.21c-.3-.38-.58-.8-.84-1.25a8.51 8.51 0 0 1-1.12-4.2v-4.01c0-1.18.75-2.22 1.86-2.61z"
                                                    opacity="1" data-original="#000000" class=""></path>
                                                <path
                                                    d="m23.491 11.826-5.25-1.786a.737.737 0 0 0-.482 0l-5.25 1.786a.748.748 0 0 0-.509.71v4.018c0 4.904 5.474 7.288 5.707 7.387a.754.754 0 0 0 .586 0c.233-.1 5.707-2.483 5.707-7.387v-4.018a.748.748 0 0 0-.509-.71zm-2.205 3.792-2.75 3.5a1 1 0 0 1-1.437.142l-1.75-1.5a1 1 0 1 1 1.301-1.518l.958.821 2.105-2.679a.998.998 0 0 1 1.404-.168.996.996 0 0 1 .169 1.402z"
                                                    opacity="1" data-original="#000000" class=""></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="verify-item__content">
                                        <span class="verify-item__title"> @lang('Verified Email') </span>
                                        <p class="verify-item__text">
                                            @lang('Verified :fullname email', ['fullname' => $freelancer->fullname])
                                        </p>
                                    </div>
                                </a>

                                <a href="javascript:void(0)" class="verify-item">
                                    <span class="verify-item__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 24 24"
                                            style="enable-background:new 0 0 512 512" xml:space="preserve"
                                            class="">
                                            <g>
                                                <path
                                                    d="M22 19c0 .258-.043.504-.104.742l-4.508-5.285L22 10.728zm-6.166-3.285-3.205 2.592a1.002 1.002 0 0 1-1.258 0l-3.166-2.561-4.896 5.729C3.791 21.805 4.373 22 5 22h14c.643 0 1.234-.207 1.723-.552zM2 10.728V19c0 .274.049.535.119.789l4.529-5.301zM20 3v6.773l-8 6.471-8-6.471V3s0-.922 1-1h14.001A1 1 0 0 1 20 3zm-3.616 1.97a.999.999 0 0 0-1.414 0l-3.917 3.917L9.03 6.864a.999.999 0 1 0-1.414 1.414l2.729 2.729a.997.997 0 0 0 1.414 0l4.624-4.624a.999.999 0 0 0 .001-1.413z"
                                                    opacity="1" data-original="#000000" class=""></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="verify-item__content">
                                        <span class="verify-item__title"> @lang('Verified Mobile') </span>
                                        <p class="verify-item__text">
                                            @lang('Verified :fullname mobile', ['fullname' => $freelancer->fullname])
                                        </p>
                                    </div>
                                </a>

                                <a href="javascript:void(0)" class="verify-item">
                                    <span class="verify-item__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 24 24"
                                            style="enable-background:new 0 0 512 512" xml:space="preserve"
                                            class="">
                                            <g>
                                                <path
                                                    d="M18.5 13.8a4 4 0 0 0-4 4 3.921 3.921 0 0 0 .58 2.06 3.985 3.985 0 0 0 6.84 0 3.921 3.921 0 0 0 .58-2.06 4 4 0 0 0-4-4zm2.068 3.565-2.133 1.971a.751.751 0 0 1-1.039-.02l-.986-.986a.75.75 0 1 1 1.061-1.06l.475.475 1.6-1.481a.749.749 0 1 1 1.017 1.1zM1.5 6.8v-.46A4.141 4.141 0 0 1 5.64 2.2h11.71a4.15 4.15 0 0 1 4.15 4.15v.45a1 1 0 0 1-1 1h-18a1 1 0 0 1-1-1zm13.135 7.023a5.17 5.17 0 0 1 2.005-1.211 5.55 5.55 0 0 1 3.533.013 1 1 0 0 0 1.327-.937V10.3a1 1 0 0 0-1-1h-18a1 1 0 0 0-1 1v4.96a4.141 4.141 0 0 0 4.14 4.14h6.26a1.011 1.011 0 0 0 1.026-1.069 5.522 5.522 0 0 1 1.709-4.508zM7.5 16.05h-2a.75.75 0 0 1 0-1.5h2a.75.75 0 0 1 0 1.5z"
                                                    data-name="1" opacity="1" data-original="#000000"
                                                    class=""></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="verify-item__content">
                                        @if ($freelancer->work_profile_complete)
                                            <span class="verify-item__title"> @lang('Profile Verified') </span>
                                            <p class="verify-item__text">
                                                @lang('Verified :fullname profile', ['fullname' => $freelancer->fullname])
                                            </p>
                                        @else
                                            <span class="verify-item__title"> @lang('Profile Unverified') </span>
                                            <p class="verify-item__text unverified-text">
                                                @lang('Your profile is not yet verified. Please complete your profile to become verified.', ['fullname' => $freelancer->fullname])
                                            </p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="sidebar-item">
                            <h6 class="sidebar-item__title"> @lang('Top Skilled Jobs') </h6>
                            <ul class="performer-list">
                                @forelse ($topSkills as $skill)
                                    <li class="performer-list__item">
                                        <span class="text"> {{ __($skill['name']) }}</span>
                                        <span class="value"> {{ $skill['count'] }} </span>
                                    </li>
                                @empty
                                    <div class="empty-message text-center py-5">
                                        <img src="{{ asset(activeTemplate(true) . 'images/empty.png') }}" alt="empty">
                                        <p class="text-muted mt-3">@lang('No top skill found!')</p>
                                    </div>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <!--================== sidebar end here ==================== -->
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            "use strict";

            $('#copyBtn').on('click', async function(event) {
                event.stopPropagation();
                var link = $(this).data('link');
                try {
                    await navigator.clipboard.writeText(link);
                    $(this).find('i.fa-copy').addClass('copied');
                    setTimeout(() => {
                        $(this).find('i.fa-copy').removeClass('copied');
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy text: ', err);
                }
            });
        });
    </script>
@endpush

@push('style')
    <style>
        .dropdown-menu.share-action {
            width: 417px;
            padding: 10px;
            background-color: hsl(var(--white));
            box-shadow: -1px 7px 19px 3px hsl(var(--black) / 0.25);
        }

        .share-action .copy-link {
            border-radius: 5px;
            border: 1px solid hsl(var(--black) / 0.15);
            margin-bottom: 15px;
        }

        .share-action .copy-link .copy-btn {
            background-color: hsl(var(--gray-white));
            border: 0;
            border-left: 1px solid hsl(var(--border-color));
            cursor: pointer;
            gap: 5px;
        }

        .share-action .social-list {
            gap: 6px;
            margin-top: 10px;
            justify-content: space-between;
        }


        .share-action .copy-link .copy-input {
            padding: 5px 2px 5px 0;
            font-size: 14px;
        }

        .share-action .social-list .social-btn {
            color: hsl(var(--white)) !important;
            padding: 5px 10px;
            gap: 5px;
            border-radius: 5px;
            font-size: 14px;
        }

        .share-action .social-list .facebook {
            background-color: #1877f2;
        }

        .share-action .social-list .linkedin {
            background-color: #0a66c2;
        }

        .share-action .social-list .twitter {
            background-color: #1d9bf0;
        }

        .share-action .social-list .instagram {
            background-color: #d62976;
        }

        .share-action .copy-link .input-group-text::after {
            display: none;
        }

        .share-action .copy-link .form--control:disabled {
            background-color: transparent !important;
            border: 0;
            color: hsl(var(--black)/ 0.5);
        }

        @media screen and (max-width:425px) {
            .dropdown-menu.share-action {
                width: 300px;
                padding: 5px;
            }

            .share-action .social-list .social-btn {
                padding: 5px 8px;
            }

        }

        .header-share .close-preview {
            position: absolute;
            width: 20px;
            height: 20px;
            display: grid;
            place-items: center;
        }

        .header-share .share-card__share .page-share-btn {
            flex: 1;
            border: 1px solid hsl(var(--border-color)) !important;
            border-radius: 10px;
        }

        .profile-action-btn .nav-menu .nav-link {
            padding: 0 !important;
        }

        .profile-action-btn .nav-menu .nav-link::before {
            display: none !important;
        }

        .dropdown-menu__list .social-btn {
            padding: 5px 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: hsl(var(--heading-color));
            transition: .2s linear
        }

        .dropdown-menu__list .social-btn:hover {
            background-color: hsl(var(--base));
            color: hsl(var(--white));
        }

        .profile-wrapper .profile-action-btn .dropdown-menu {
            right: 0 !important;
            left: unset !important;
            margin-top: 5px;
        }

        .profile-wrapper .profile-action-btn .nav-menu {
            margin-top: 0 !important;
        }

        .profile-action-btn .form--control.form-control {
            padding: 10px 12px !important;
            font-size: 12px;
        }

        @media (max-width:1199px) {
            .nav-menu .dropdown-menu {
                position: absolute !important;
                margin-top: 10px !important;
            }

            .dropdown-menu.show {
                display: block;
                background: hsl(var(--white)) !important;
            }
        }

        /* End-Share-btn */
    </style>
@endpush
