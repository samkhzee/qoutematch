@extends('Template::layouts.app')
@section('panel')
    @php
        $banned = getContent('banned.content', true);
    @endphp
    <div class="user-data-section">
        <div class="container">
            <div class="user-data-section__content">
                <div class="container">
                    <div class="text-center ban-wrapper">
                        <a href="{{ route('home') }}" class="ban-wrapper__logo">
                            <img src="{{ siteLogo() }}" alt="">
                        </a>
                        <img class="ban-wrapper-image"
                            src="{{ getImage('assets/images/frontend/banned/' . @$banned->data_values->image, '360x370') }}"
                            alt="@lang('image')">
                        <h3 class="ban-wrapper-title">{{ __(@$banned->data_values->heading) }}</h3>
                        <p class="ban-wrapper-desc">{{ __($user->ban_reason) }} </p>
                        <a class="btn btn--xl btn--base" href="{{ route('home') }}"> @lang('Go to Home') </a>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('style-lib')
        <link href="{{ asset(activeTemplate(true) . 'css/main.css') }}" rel="stylesheet">
    @endpush
    @push('script-lib')
        <script src="{{ asset(activeTemplate(true) . 'js/main.js') }}"></script>
    @endpush
