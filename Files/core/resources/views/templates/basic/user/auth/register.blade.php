@extends('Template::layouts.app')
@section('panel')
    @php
        $switchingBtn = getContent('switching_button.content', true)->data_values;
        $register = getContent('register.content', true)->data_values;
        $banner = getContent('banner.content', true)->data_values;
    @endphp

    <section class="account">
        <div class="account-inner">
            <div class="account-inner__left">
                <div class="account-inner__shape">
                    <img src="{{ frontendImage('banner', @$banner->shape, '475x630') }}" alt="">
                </div>
                <div class="account-thumb">
                    <img src="{{ frontendImage('register', @$register->image, '770x670') }}" alt="">
                </div>
            </div>
            <div class="account-inner__right">


                <div class="account-form-wrapper">
                    <a href="{{ route('home') }}" class="account-form__logo">
                        <img src="{{ siteLogo() }}" alt="">
                    </a>
                    <form action="{{ route('user.register') }}" method="POST"
                        class="verify-gcaptcha disableSubmission registerForm">
                        @csrf
                        <div class="account-form">
                            <div class="radio-btn-wrapper">
                                <div class="form--radio">
                                    <input class="form-check-input" type="radio" name="join-wrapper" id="join-freelancer"
                                        value="1" @if (Route::currentRouteName() == 'user.register') checked @endif
                                        onclick="window.location='{{ route('user.register') }}'">
                                    <label class="form-check-label" for="join-freelancer">
                                        <span class="text">{{ __($switchingBtn->freelancer_register_button) }}</span>
                                    </label>
                                </div>
                                <div class="form--radio">
                                    <input class="form-check-input" type="radio" name="join-wrapper" id="join-buyer"
                                        value="2" @if (Route::currentRouteName() == 'buyer.register') checked @endif
                                        onclick="window.location='{{ route('buyer.register') }}'">
                                    <label class="form-check-label" for="join-buyer">
                                        <span class="text">{{ __($switchingBtn->buyer_register_button) }} </span>
                                    </label>
                                </div>
                            </div>
                            <p class="text"> @lang('Welcome to') {{ __(gs('site_name')) }}</p>
                            <h5 class="account-form__title"> {{ __(@$register->heading) }}</h5>

                            <div class="@if (!gs('registration')) form-disabled p-3 @endif">
                                @if (!gs('registration'))
                                    <a class="form-disabled-text" data-bs-toggle="tooltip" href="{{ route('user.login') }}"
                                        title="@lang('We are unable to process your registration at this time.')">
                                        <svg style="enable-background:new 0 0 512 512" xmlns="http://www.w3.org/2000/svg"
                                            version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="100"
                                            height="100" x="0" y="0" viewBox="0 0 512 512" xml:space="preserve">
                                            <g>
                                                <path data-original="#{{ gs('base_color') }}"
                                                    d="M255.999 0c-79.044 0-143.352 64.308-143.352 143.353v70.193c0 4.78 3.879 8.656 8.659 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193c0-42.998 34.981-77.98 77.979-77.98s77.979 34.982 77.979 77.98v70.193c0 4.78 3.88 8.656 8.661 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193C399.352 64.308 335.044 0 255.999 0zM382.04 204.89h-30.748v-61.537c0-52.544-42.748-95.292-95.291-95.292s-95.291 42.748-95.291 95.292v61.537h-30.748v-61.537c0-69.499 56.54-126.04 126.038-126.04 69.499 0 126.04 56.541 126.04 126.04v61.537z"
                                                    fill="#{{ gs('base_color') }}" opacity="1"></path>
                                                <path data-original="#{{ gs('base_color') }}"
                                                    d="M410.63 204.89H101.371c-20.505 0-37.188 16.683-37.188 37.188v232.734c0 20.505 16.683 37.188 37.188 37.188H410.63c20.505 0 37.187-16.683 37.187-37.189V242.078c0-20.505-16.682-37.188-37.187-37.188zm19.875 269.921c0 10.96-8.916 19.876-19.875 19.876H101.371c-10.96 0-19.876-8.916-19.876-19.876V242.078c0-10.96 8.916-19.876 19.876-19.876H410.63c10.959 0 19.875 8.916 19.875 19.876v232.733z"
                                                    fill="#{{ gs('base_color') }}" opacity="1">
                                                </path>
                                                <path data-original="#{{ gs('base_color') }}"
                                                    d="M285.11 369.781c10.113-8.521 15.998-20.978 15.998-34.365 0-24.873-20.236-45.109-45.109-45.109-24.874 0-45.11 20.236-45.11 45.109 0 13.387 5.885 25.844 16 34.367l-9.731 46.362a8.66 8.66 0 0 0 8.472 10.436h60.738a8.654 8.654 0 0 0 8.47-10.434l-9.728-46.366zm-14.259-10.961a8.658 8.658 0 0 0-3.824 9.081l8.68 41.366h-39.415l8.682-41.363a8.655 8.655 0 0 0-3.824-9.081c-8.108-5.16-12.948-13.911-12.948-23.406 0-15.327 12.469-27.796 27.797-27.796 15.327 0 27.796 12.469 27.796 27.796.002 9.497-4.838 18.246-12.944 23.403z"
                                                    fill="#{{ gs('base_color') }}" opacity="1"></path>
                                            </g>
                                        </svg>
                                    </a>
                                @endif

                                @include('Template::partials.social_login')

                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="firstname" class="form--label"> @lang('First Name') </label>
                                        <input type="text" class="form-control form--control" name="firstname"
                                            value="{{ old('firstname') }}" id="firstname" autocomplete="off" required>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="lastname" class="form--label"> @lang('Last Name') </label>
                                        <input type="text" class="form-control form--control" name="lastname"
                                            value="{{ old('lastname') }}" id="lastname" autocomplete="off" required>
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <label for="email" class="form--label"> @lang('Email Address') </label>
                                        <input type="text" class="form--control form-control checkUser" name="email"
                                            value="{{ old('email') }}" id="email" autocomplete="off" required>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label for="your-password" class="form--label">@lang('Password')</label>
                                        <div class="position-relative">
                                            <input id="your-password" type="password" name="password"
                                                class="form--control form-control @if (gs('secure_password')) secure-password @endif"
                                                autocomplete="off" required>
                                            <span class="password-show-hide fa-solid fa-eye toggle-password"
                                                id="toggle-password" aria-label="Toggle password visibility"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="confirm-password" class="form--label">@lang('Confirm Password')</label>
                                        <div class="position-relative">
                                            <input id="confirm-password" name="password_confirmation" type="password"
                                                class="form--control form-control" required>
                                            <span class="password-show-hide fa-solid fa-eye toggle-password"
                                                id="toggle-confirm-password"
                                                aria-label="Toggle confirm password visibility"></span>
                                        </div>
                                    </div>

                                    <x-captcha />

                                    @if (gs('agree'))
                                        @php
                                            $policyPages = getContent('policy_pages.element', false, orderById: true);
                                        @endphp
                                        <div class="form-group form--check mb-2 flex-nowrap">
                                            <input type="checkbox" class="form-check-input" id="agree"
                                                @checked(old('agree')) name="agree" required>
                                            <label for="agree" class="mx-2">
                                                @lang('I agree with')
                                                @foreach ($policyPages as $policy)
                                                    <a href="{{ route('policy.pages', $policy->slug) }}"
                                                        target="__blank">{{ __($policy->data_values->title) }}</a>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </label>
                                        </div>
                                    @endif

                                    <div class="col-sm-12 form-group">
                                        <button type="submit" id="recaptcha" class="btn btn--base w-100">
                                            @lang('Register Account') </button>
                                    </div>
                                </div>
                            </div>


                            <p class="account-form__text"> @lang('Already have an account?')
                                <a href="{{ route('user.login') }}" class="text--base apply-form"> @lang('Login Now')
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="{{ route('user.login') }}" class="btn btn--base">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>

@endsection



@if (gs('registration'))

    @if (gs('secure_password'))
        @push('script-lib')
            <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
        @endpush
    @endif

    @push('script')
        <script>
            "use strict";
            (function($) {
                $('.checkUser').on('focusout', function(e) {
                    var url = '{{ route('user.checkUser') }}';
                    var value = $(this).val();
                    var token = '{{ csrf_token() }}';

                    var data = {
                        email: value,
                        _token: token
                    }

                    $.post(url, data, function(response) {
                        if (response.data != false) {
                            $('#existModalCenter').modal('show');
                        }
                    });
                });

            })(jQuery);
        </script>
    @endpush
@else
    @push('style')
        <style>
            .account-form .form--control {
                backdrop-filter: blur(5px) !important;
                background-color: rgb(255 255 255 / 10%) !important;
            }

            .form-disabled {
                overflow: hidden;
                position: relative;
                border-radius: 25px;
            }

            .form-disabled::after {
                content: "";
                position: absolute;
                height: 100%;
                width: 100%;
                background-color: rgba(255, 255, 255, 0.2);
                top: 0;
                left: 0;
                backdrop-filter: blur(2px);
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
                z-index: 99;
            }

            .form-disabled-text {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 991;
                font-size: 24px;
                height: auto;
                width: 100%;
                text-align: center;
                color: hsl(var(--dark-600));
                font-weight: 800;
                line-height: 1.2;
            }
        </style>
    @endpush

    @push('script')
        <script>
            "use strict";
            (function($) {
                @if (!gs('registration'))
                    notify('error', 'Registration is currently disabled!');
                @endif
            })(jQuery);
        </script>
    @endpush

@endif
