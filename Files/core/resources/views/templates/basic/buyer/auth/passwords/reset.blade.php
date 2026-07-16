@extends('Template::layouts.frontend')
@section('content')
    <div class="account-section my-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-5">
                    <div class="card custom--card">
                        <div class="card-body">
                            <div class="mb-4">
                                <p>@lang('Your account is verified successfully. Now you can change your password. Please enter a strong password and don\'t share it with anyone.')</p>
                            </div>
                            <form method="POST" action="{{ route('buyer.password.update') }}">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                <input type="hidden" name="token" value="{{ $token }}">
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
                                <div class="form-group">
                                    <label for="confirm-password" class="form--label">@lang('Confirm Password')</label>
                                    <div class="position-relative">
                                        <input id="confirm-password" name="password_confirmation" type="password"
                                            class="form--control form-control" required>
                                        <span class="password-show-hide fa-solid fa-eye toggle-password"
                                            id="toggle-confirm-password"
                                            aria-label="Toggle confirm password visibility"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100"> @lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
