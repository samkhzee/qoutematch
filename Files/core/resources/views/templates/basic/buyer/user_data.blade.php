@extends('Template::layouts.app')
@section('panel')
    <div class="user-data-section">
        <div class="container">
            <div class="user-data-section__content">
                <div class="row justify-content-center my-120">
                    <div class="col-md-10 col-xl-6">
                        <div class="card custom--card">
                            <div class="card-body">
                                <div class="proposal-top-content text-center">
                                    <a href="{{ route('home') }}" class="user-data-header__logo">
                                        <img src="{{ siteLogo() }}" alt="">
                                    </a>
                                    <h5 class="title">@lang('Complete Profile for a Better Experience')</h5>
                                </div>

                                <form method="POST" action="{{ route('buyer.data.submit') }}">
                                    @csrf

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0 ps-3">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label class="form--label">@lang('Username')</label>
                                                <input type="text" class="form-control form--control checkBuyer"
                                                    name="username"
                                                    value="{{ old('username', $user->username ?: ($suggestedUsername ?? '')) }}"
                                                    required>
                                                <small class="text-muted d-block mt-1">Use lowercase letters, numbers, and underscores only (not your email).</small>
                                                @error('username')<small class="text--danger d-block">{{ $message }}</small>@enderror
                                                <small class="text--danger usernameExist"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form--label">@lang('Country')</label>
                                                <select name="country" class="form-control form--control  select2" required>
                                                    @foreach ($countries as $key => $country)
                                                        <option data-mobile_code="{{ $country->dial_code }}"
                                                            value="{{ $country->country }}" data-code="{{ $key }}">
                                                            {{ __($country->country) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form--label">@lang('Mobile')</label>
                                                <div class="input-group ">
                                                    <span class="input-group-text mobile-code">

                                                    </span>
                                                    <input type="hidden" name="mobile_code">
                                                    <input type="hidden" name="country_code">
                                                    <input type="number" name="mobile" value="{{ old('mobile') }}"
                                                        class="form-control form-control form--control  checkBuyer"
                                                        required>
                                                </div>
                                                <small class="text--danger mobileExist"></small>
                                                @error('mobile')<small class="text--danger d-block">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form--label">@lang('Address')</label>
                                            <input type="text" class="form-control form--control " name="address"
                                                value="{{ old('address') }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form--label">@lang('State')</label>
                                            <input type="text" class="form-control form--control " name="state"
                                                value="{{ old('state') }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form--label">@lang('Zip Code')</label>
                                            <input type="text" class="form-control form--control " name="zip"
                                                value="{{ old('zip') }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form--label">@lang('City')</label>
                                            <input type="text" class="form-control form--control " name="city"
                                                value="{{ old('city') }}">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" id="joinButton" class="btn btn--base w-100">
                                                @lang('Submit')
                                            </button>
                                        </div>
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

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('.role-radio').on('change', function() {
                var selectedRole = $(this).val();
                var joinButton = $('#joinButton');

                if (selectedRole === 'buyer') {
                    joinButton.text("@lang('Join as Buyer')");
                } else if (selectedRole === 'freelancer') {
                    joinButton.text("@lang('Join as Freelancer')");
                }
            });



            $('.select2').select2();

            $('select[name=country]').on('change', function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
                var value = $('[name=mobile]').val();
                var name = 'mobile';
                checkBuyer(value, name);
            });

            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));


            $('.checkBuyer').on('focusout', function(e) {
                var value = $(this).val();
                var name = $(this).attr('name')
                checkBuyer(value, name);
            });

            function checkBuyer(value, name) {
                var url = '{{ route('buyer.checkBuyerLogged') }}';
                var token = '{{ csrf_token() }}';

                if (name == 'mobile') {
                    var mobile = `${value}`;
                    var data = {
                        mobile: mobile,
                        mobile_code: $('.mobile-code').text().substr(1),
                        _token: token
                    }
                }
                if (name == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.field} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            }
            $('select[name=country]')
                .wrap(`<div class="position-relative"></div>`)
                .select2({
                    dropdownParent: $('select[name=country]').parent(),
                });

        })(jQuery);
    </script>
@endpush
