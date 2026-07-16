@extends('Template::layouts.frontend')
@section('content')
    <section class="blog-detials py-60">
        <div class="container">
            <div class="row gy-5 justify-content-center">
                <div class="col-xl-9 col-lg-8">
                    <div class="blog-details">
                        <div class="blog-details__thumb">
                            <img src="{{ frontendImage('blog', @$blog->data_values->image, '970x600') }}" class="fit-image"
                                alt="blog">
                        </div>
                        <div class="blog-details__content">
                            <span class="blog-item__date text--base mb-2"><span class="blog-item__date-icon"><i
                                        class="las la-clock"></i></span>
                                {{ showDateTime(@$blog->created_at, 'F d Y') }} </span>
                            <h4 class="blog-details__title">{{ __(@$blog->data_values->title) }}</h4>

                            <p class="blog-details__desc">
                                @php  echo @$blog->data_values->description @endphp
                            </p>
                            <div class="blog-details__share mt-4 d-flex align-items-center flex-wrap justify-content-start">
                                <h6 class="social-share__title mb-0 me-sm-3 me-1 d-inline-block">@lang('Share') :</h6>
                                <ul class="social-list">
                                    <li class="social-list__item"><a
                                            href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                            class="social-list__link flex-center" target="__blank"><i
                                                class="fab fa-facebook-f"></i></a>
                                    </li>
                                    <li class="social-list__item"><a
                                            href="https://twitter.com/share?url={{ url()->current() }}"
                                            class="social-list__link flex-center" target="__blank"> <i
                                                class="fa-brands fa-x-twitter"></i></a></li>
                                    <li class="social-list__item"><a
                                            href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}"
                                            class="social-list__link flex-center" target="__blank"> <i
                                                class="fab fa-linkedin-in"></i></a>
                                    </li>
                                    <li class="social-list__item"><a
                                            href="https://wa.me/?text={{ urlencode(url()->current()) }}"
                                            class="social-list__link flex-center" target="__blank"> <i
                                                class="fab fa-whatsapp"></i></a></li>
                                    <li class="social-list__item"><a
                                            href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->data_values->title) }}"
                                            class="social-list__link flex-center" target="__blank"> <i
                                                class="fab fa-telegram"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="fb-comments" data-href="{{ url()->current() }}" data-numposts="5"></div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    <!-- ============================= Blog Details Sidebar Start ======================== -->
                    <div class="blog-sidebar-wrapper">
                        <div class="blog-sidebar">
                            <h5 class="blog-sidebar__title">@lang('Latest Blogs')</h5>
                        </div>
                        <div class="blog-sidebar">
                            @forelse ($latestBlogs as $blog)
                                <div class="latest-blog">
                                    <a href="{{ route('blog.details', $blog->slug) }}" class="latest-blog__thumb"><img
                                            src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->image, '370x175') }}"
                                            class="fit-image" alt=""></a>
                                    <div class="latest-blog__content">
                                        <h6 class="latest-blog__title"><a
                                                href="{{ route('blog.details', $blog->slug) }}">{{ __(strLimit(@$blog->data_values->title, 80)) }}</a>
                                        </h6>
                                        <span class="latest-blog__date fs-13 text--base"><i class="las la-clock"></i>
                                            {{ showDateTime(@$blog->created_at, 'F d Y') }}</span>
                                    </div>
                                </div>
                            @empty
                                <span class="latest-blog"> @lang('Latest blog not found!')</span>
                            @endforelse
                        </div>
                    </div>
                    <!-- ============================= Blog Details Sidebar End ======================== -->
                </div>
            </div>
        </div>
    </section>
@endsection


@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush


@push('style')
    <style>
        .blog-details {
            border-radius: 8px;
            overflow: hidden;
            height: 100%;
        }

        .blog-details__thumb {
            height: 600px;
            max-height: 100%;
            border-radius: 10px;
            overflow: hidden;
        }

        @media screen and (max-width: 1199px) {
            .blog-details__thumb {
                height: 450px;
            }
        }

        @media screen and (max-width: 767px) {
            .blog-details__thumb {
                height: 300px;
            }
        }

        .blog-details__content {
            padding-top: 24px;
        }

        .blog-details__content h6{
            font-weight: 600;
        }

        .blog-details__title {
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .blog-item__date-icon {
            font-size: 24px;
        }

        .blog-item__date {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .blog-details__desc {
            margin-bottom: 24px;
        }

        .blog-details .blog-item__text {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            gap: 5px;
            font-size: 18px;
        }

        @media screen and (max-width: 424px) {
            .blog-details .blog-item__text {
                font-size: 15px;
            }
        }

        .blog-details .blog-item__text-icon {
            color: hsl(var(--base));
        }

        .blog-details .social-list__link {
            border: 1px solid hsl(var(--border-color));
            color: hsl(var(--text-color));
            width: 35px;
            height: 35px;
            background-color: hsl(var(--white)/.1);
            font-size: 16px;
        }

        .blog-details__share-title {
            color: hsl(var(--base));
            margin-bottom: 16px;
            font-weight: 500;
        }

        .blog-details__share .social-list {
            gap: 8px;
        }

        .blog-details__share .social-list__link:hover {
            color: hsl(var(--white)) !important;
            background-color: hsl(var(--base));
        }

        .quote-text {
            padding-left: 20px;
            border-left: 3px solid hsl(var(--base));
            margin-bottom: 20px;
        }

        @media screen and (max-width: 767px) {
            .quote-text {
                padding: 15px;
            }
        }

        @media screen and (max-width: 575px) {
            .quote-text {
                padding: 5px 15px;
            }
        }

        .quote-text__desc {
            color: hsl(var(--text-color-two));
            font-style: italic;
        }
    </style>
@endpush
