@extends('Template::layouts.app')
@section('panel')
    @php
        $login = getContent('login.content', true)->data_values;
        $switchingBtn = getContent('switching_button.content', true)->data_values;
        $banner = getContent('banner.content', true)->data_values;
    @endphp

    <section class="account">
        <div class="account-inner">
            <div class="account-inner__left">
                <div class="account-inner__shape">
                    <img src="{{ frontendImage('banner', @$banner->shape, '475x630') }}" alt="">
                </div>
                <div class="account-thumb">
                    <img src="{{ frontendImage('login', @$login->image, '770x670') }}" alt="">
                </div>
            </div>
            <div class="account-inner__right">
                <div class="account-form-wrapper">
                    <a href="{{ route('home') }}" class="account-form__logo">
                        <img src="{{ siteLogo() }}" alt="">
                    </a>
                    <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha loginForm">
                        @csrf
                        <div class="account-form">
                            <div class="radio-btn-wrapper">
                                <div class="form--radio">
                                    <input class="form-check-input" type="radio" name="apply-wrapper"
                                        id="apply-freelancer" value="1"
                                        @if (Route::currentRouteName() == 'user.login') checked @endif
                                        onclick="window.location='{{ route('user.login') }}'">
                                    <label class="form-check-label" for="apply-freelancer">
                                        <span class="text">{{ __($switchingBtn->freelancer_login_button) }}</span>
                                    </label>
                                </div>
                                <div class="form--radio">
                                    <input class="form-check-input" type="radio" name="apply-wrapper" id="apply-buyer"
                                        value="2" @if (Route::currentRouteName() == 'buyer.login') checked @endif
                                        onclick="window.location='{{ route('buyer.login') }}'">
                                    <label class="form-check-label" for="apply-buyer">
                                        <span class="text">{{ __($switchingBtn->buyer_login_button) }} </span>
                                    </label>
                                </div>
                            </div>

                            <p class="text"> @lang('Welcome Back') </p>
                            <h5 class="account-form__title"> {{ __(@$login->heading) }}</h5>

                            @include('Template::partials.social_login')

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="username" class="form--label"> @lang('Username or Email') </label>
                                        <input type="text" name="username" value="{{ old('username') }}"
                                            class="form-control form--control" id="username" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="your-password" class="form--label">@lang('Password')</label>
                                        <div class="position-relative">
                                            <input id="your-password" type="password" name="password"
                                                class="form--control form-control @if (gs('secure_password')) secure-password @endif"
                                                autocomplete="off" required>
                                            <span class="password-show-hide fa-solid fa-eye toggle-password"
                                                id="toggle-password" aria-label="Toggle password visibility"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="flex-between">
                                            <div class="form--check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="flexCheckChecked" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="flexCheckChecked">
                                                    @lang('Remember Me')
                                                </label>
                                            </div>
                                            <a href="{{ route('user.password.request') }}" class="forgot-password">
                                                @lang('Forgot password?') </a>
                                        </div>
                                    </div>
                                </div>

                                <x-captcha />

                                <div class="col-12 form-group">
                                    <button type="submit" class="btn btn--base w-100"> @lang('Login Account') </button>
                                </div>
                            </div>
                            <p class="account-form__text"> @lang('Don\'t have on account yet?')
                                <a href="{{ route('user.register') }}" class="text--base "> @lang('Create Account') </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
