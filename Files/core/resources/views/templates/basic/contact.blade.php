@extends('Template::layouts.frontend')
@section('content')
    @php
        $socialIcons = getContent('social_icon.element', orderById: true);
        $contact = getContent('contact_us.content', true)->data_values;
    @endphp

    <div class="contact-section  mb-120">
        <div class="container">
            <div class="row gy-4 justify-content-between align-items-center flex-wrap-reverse">
                <div class="col-xl-4">
                    <div class="contact-item-wrapper">
                        <h5 class="contact-item-wrapper__title">{{ __(@$contact->title) }}</h5>
                        <div>
                            <div class="contact-item">
                                <span class="contact-item__icon">
                                    <i class="fa-solid fa-house-user"></i>
                                </span>
                                <div class="contact-item__content">
                                    <p class="contact-item__title">@lang(' Office Address') </p>
                                    <p class="contact-item__desc">
                                        {{ __(@$contact->contact_details) }}
                                    </p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="contact-item__icon">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </span>
                                <div class="contact-item__content">
                                    <p class="contact-item__title"> @lang('Email Address') </p>
                                    <p class="contact-item__desc">
                                        <a href="mailto:{{ @$contact->email_address }}" title="@lang('E-mail us')"
                                            class="link">{{ __(@$contact->email_address) }}</a>
                                    </p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="contact-item__icon">
                                    <i class="fa-solid fa-phone-volume"></i>
                                </span>
                                <div class="contact-item__content">
                                    <p class="contact-item__title"> @lang('Phone Number') </p>
                                    <p class="contact-item__desc">
                                        <a href="tel:{{ @$contact->contact_number }}" title="@lang('Call Us')"
                                            class="link">{{ __(@$contact->contact_number) }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="contact-item-wrapper__bottom">
                            <div class="social-list-wrapper">
                                <p class="title"> Follow Us </p>
                                <ul class="social-list">
                                    @foreach ($socialIcons as $social)
                                        <li class="social-list__item"><a href="{{ @$social->data_values->url }}"
                                                target="_blank" title="{{ __(@$social->data_values->title) }}"
                                                class="social-list__link flex-center">@php echo $social->data_values->social_icon @endphp</a> </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7">
                    <div class="contact-form-wrapper">
                        <h4 class="contact-form-wrapper__title">{{ __(@$contact->heading) }}</h4>
                        <p class="contact-form-wrapper__desc">{{ __(@$contact->subheading) }}
                        </p>
                        <form method="post" class="verify-gcaptcha verify-form">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label class="form--label">@lang('Name')</label>
                                    <input name="name" type="text" class="form-control form--control"
                                        value="{{ old('name', @$user->fullname) }}"
                                        @if ($user && $user->profile_complete) readonly @endif required>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label class="form--label">@lang('Email')</label>
                                    <input name="email" type="email" class="form-control form--control"
                                        value="{{ old('email', @$user->email) }}"
                                        @if ($user) readonly @endif required>
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label class="form--label">@lang('Subject')</label>
                                    <input name="subject" type="text" class="form-control form--control"
                                        value="{{ old('subject') }}" required>
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label class="form--label">@lang('Message')</label>
                                    <textarea name="message" class="form-control form--control" required>{{ old('message') }}</textarea>
                                </div>
                                <x-captcha />
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Send Message')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include('Template::sections.' . $sec)
        @endforeach
    @endif
@endsection

@push('style')
    <style>
        .contact-section .social-list__link {
            width: 38px;
            height: 38px;
            color: hsl(var(--base));
            font-size: 14px;
            border: 1px solid hsl(var(--base));
            background-color: hsl(var(--white) / 0.15) !important;
        }

        .contact-section .social-list__link:hover {

            color: hsl(var(--white)) !important;
            background-color: hsl(var(--base)) !important;
        }
    </style>
@endpush
