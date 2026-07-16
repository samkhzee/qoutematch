@php
    use App\Models\Language;
    $languages = Language::get();
    $defaultLang = $languages->firstWhere('is_default', Status::YES);
    $currentLangCode = session('lang', config('app.locale'));
    $currentLang = $languages->firstWhere('code', $currentLangCode) ?: $defaultLang;
@endphp

<header class="header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-xl navbar-light">
            <a class="navbar-brand logo" href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt=""></a>
            <div class="d-xl-none d-block job-link">
                <a href="{{ route('buyer.job.post.details') }}" class="btn btn--base btn--sm">
                    @lang('Post Job')
                </a>
            </div>

            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu me-auto align-items-xl-center">
                    <li class="nav-item {{ menuActive('home') }}">
                        <a class="nav-link" aria-current="page" href="{{ route('home') }}"> @lang('Home') </a>
                    </li>
                    @if (count($pages) > 1)
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="javascript:void(0)" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                @lang('Pages') <span class="nav-item__icon"><i class="las la-angle-down"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($pages as $k => $data)
                                    <li class="dropdown-menu__list {{ menuActive('pages', null, @$data->slug) }}">
                                        <a class="dropdown-item dropdown-menu__link"
                                            href="{{ route('pages', $data->slug) }}">
                                            {{ __($data->name) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        @foreach ($pages as $k => $data)
                            <li class="nav-item {{ menuActive('pages', null, @$data->slug) }}">
                                <a class="nav-link" href="{{ route('pages', $data->slug) }}">{{ __($data->name) }}</a>
                            </li>
                        @endforeach
                    @endif

                    <li class="nav-item {{ menuActive(['freelance.jobs', 'explore.bid.job']) }}">
                        <a class="nav-link " href="{{ route('freelance.jobs') }}"> @lang('Find Jobs') </a>
                    </li>
                    <li class="nav-item {{ menuActive(['all.freelancers', 'talent.explore']) }}">
                        <a class="nav-link" href="{{ route('all.freelancers') }}"> @lang('Find Talents') </a>
                    </li>

                    <li class="nav-item {{ menuActive(['blogs', 'blog.details']) }}">
                        <a class="nav-link" href="{{ route('blogs') }}">@lang('Blogs') </a>
                    </li>
                    <li class="nav-item {{ menuActive('contact') }}">
                        <a class="nav-link" href="{{ route('contact') }}">@lang('Contact') </a>
                    </li>
                    <li class="nav-item d-flex justify-content-between w-100 d-xl-none">
                        <div class="top-button w-100">
                            <ul class="login-registration-list d-flex flex-wrap justify-content-between align-items-center">
                                <li class="login-registration-list__item d-flex gap-3">
                                    @auth
                                        <a href="{{ route('user.home') }}" class="login-registration-list__link">
                                            @lang('Dashboard') </a>
                                    @else
                                        @if (auth()->guard('buyer')->check())
                                            <a href="{{ route('buyer.home') }}" class="login-registration-list__link">
                                                @lang('Dashboard') </a>
                                        @else
                                            <a href="{{ route('user.login') }}" class="login-registration-list__link">
                                                @lang('Login') </a>
                                            <a href="{{ route('user.register') }}" class="login-registration-list__link">
                                                @lang('Register') </a>
                                        @endif
                                    @endauth
                                </li>
                                <li class="login-registration-list__item">
                                    @if (gs('multi_language'))
                                        @include('Template::partials.language')
                                    @endif
                                </li>
                            </ul>
                        </div>

                    </li>
                </ul>
            </div>
            <div class="d-xl-block d-none">
                <div class="top-button d-flex flex-wrap justify-content-between align-items-center">

                    @if (gs('multi_language'))
                        @include('Template::partials.language')
                    @endif

                    <ul class="login-registration-list d-flex flex-wrap justify-content-between align-items-center">
                        @auth
                            <li class="login-registration-list__item">
                                <a href="{{ route('user.home') }}" class="login-registration-list__link">
                                    @lang('Dashboard') </a>
                            </li>
                        @else
                            @if (auth()->guard('buyer')->check())
                                <li class="login-registration-list__item">
                                    <a href="{{ route('buyer.home') }}" class="login-registration-list__link">
                                        @lang('Dashboard') </a>
                                </li>
                            @else
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.login') }}" class="login-registration-list__link">
                                        @lang('Login')
                                    </a>
                                </li>
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.register') }}" class="login-registration-list__link">
                                        @lang('Register') </a>
                                </li>
                            @endif
                        @endauth

                        @if (!auth()->check())
                            <li class="login-registration-list__item">
                                <a href="{{ route('buyer.job.post.details') }}" class="btn btn--base"> @lang('Post Job') </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
