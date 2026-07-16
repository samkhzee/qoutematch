@extends('Template::layouts.frontend')
@section('content')
    <div class="user-data-section">
        <div class="container">
            <div class="user-data-section__content">
                <div class="container">
                    <div class="d-flex justify-content-center">
                        <div class="verification-code-wrapper">
                            <div class="verification-area">
                                <div class="proposal-top-content">
                                    <a href="{{ route('home') }}" class="user-data-header__logo">
                                        <img src="{{ siteLogo() }}" alt="">
                                    </a>
                                    <h5 class="pb-3 text-center border-bottom">@lang('2FA Verification')</h5>
                                </div>
                                <form action="{{ route('buyer.2fa.verify') }}" method="POST" class="submit-form">
                                    @csrf

                                    @include('Template::partials.verification_code')

                                    <div class="form--group">
                                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
